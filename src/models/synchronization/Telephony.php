<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\telephony\TelephonySynchronizationFullListJob;
use wm\admin\jobs\telephony\TelephonySynchronizationFullGetJob;
use wm\admin\jobs\telephony\TelephonySynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use wm\admin\models\gii\ColumnSchema;

class Telephony extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_telephony';
    }

    public static $synchronizationFullListJob = TelephonySynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = TelephonySynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = TelephonySynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'ID';

    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'voximplant.statistic.get',
        );
        return $request['total'];
    }

    public static function getB24Fields()
    {
        return [
            "ID" => 'Идентификатор звонка (для внутренних целей)',
            "PORTAL_USER_ID" => 'Идентификатор ответившего оператора (если это тип звонка: 2 - Входящий) или идентификатор позвонившего оператора (если это тип звонка: 1 - Исходящий)',
            "PORTAL_NUMBER" => 'Номер, на который поступил звонок (если это тип звонка: 2 - Входящий) или номер, с которого был совершен звонок (1 - Исходящий)',
            "PHONE_NUMBER" => 'Номер, с которого звонит абонент (если это тип звонка: 2 - Входящий) или номер, которому звонит оператор (1 - Исходящий)',
            "CALL_ID" => 'Идентификатор звонка',
            "EXTERNAL_CALL_ID" => 'EXTERNAL_CALL_ID',
            "CALL_CATEGORY" => 'CALL_CATEGORY',
            "CALL_DURATION" => 'Продолжительность звонка в секундах',
            "CALL_START_DATE" => 'Время инициализации звонка. При сортировке по этому полю нужно указывать дату в формате ISO-8601',
            "CALL_RECORD_URL" => 'CALL_RECORD_URL',
            "CALL_VOTE" => 'По умолчанию - 0. Оценка звонка используется только для внутренней телефонии',
            "COST" => 'Стоимость звонка',
            "COST_CURRENCY" => 'Валюта звонка (RUR, USD, EUR)',
            "CALL_FAILED_CODE" => 'Код вызова',
            "CALL_FAILED_REASON" => 'Текстовое описание кода вызова (латиница)',
            "CRM_ENTITY_TYPE" => 'Тип объекта CRM, к которому прикреплено дело, например: LEAD',
            "CRM_ENTITY_ID" => 'Идентификатор объекта CRM, к которому прикреплено дело',
            "CRM_ACTIVITY_ID" => 'Идентификатор дела CRM, созданного на основании звонка',
            "REST_APP_ID" => 'Идентификатор приложения интеграции внешней телефонии',
            "REST_APP_NAME" => 'Название приложения интеграции внешней телефонии',
            "TRANSCRIPT_ID" => 'Идентификатор расшифровки звонка',
            "TRANSCRIPT_PENDING" => 'Y\N. Признак того, что расшифровка будет получена позднее',
            "SESSION_ID" => 'Идентификатор сессии звонка на стороне Voximplant',
            "REDIAL_ATTEMPT" => 'Номер попытки дозвониться (для обратных звонков)',
            "COMMENT" => 'Комментарий к звонку',
            "RECORD_FILE_ID" => 'Идентификатор файла с записью звонка',
            "CALL_TYPE" => 'Тип вызова'
        ];
    }

    public static function getB24FieldsList()
    {
        return self::getB24Fields();
    }

    public static function startSynchronization($period)
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты пользователи';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 03:00:00';
        }
        $agent->period = $period;
        $agent->status_id = 1;
        $agent->save();
    }

    public static function stopSynchronization()
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if ($agent) {
            $agent->status_id = 0;
            $agent->save();
        }
    }

    public function loadData($data)
    {
        foreach ($data as $key => $val) {
            if (in_array($key, array_keys($this->attributes))) {
                is_array($val) ? $this->$key = json_encode($val) : $this->$key = $val;
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'Telephony->loadData()');
        }
    }

    public static function createColumns(array $addFieldNames)
    {
        $fields = static::getB24Fields();
        $table = Yii::$app->db->getTableSchema(static::tableName());
        foreach ($fields as $fieldName => $fieldLable) {
            if (!isset($table->columns[$fieldName]) && in_array($fieldName, $addFieldNames)) {
                Yii::$app
                    ->db
                    ->createCommand()
                    ->addColumn(
                        $table->name,
                        $fieldName,
                        static::getDbType($fieldLable)
                    )
                    ->execute();
            }
        }
        return true;
    }

    public static function getDbType($lable)
    {
        switch ($lable) {
            case 'ID':
                return Schema::TYPE_INTEGER;
            case 'CALL_START_DATE':
                return Schema::TYPE_DATE;
            case 'TRANSCRIPT_PENDING':
                return Schema::TYPE_STRING . '(8)';
            case 'SESSION_ID':
                return Schema::TYPE_INTEGER . '(16)';
            case 'CALL_FAILED_CODE':
                return Schema::TYPE_INTEGER;
            case 'COST':
                return Schema::TYPE_INTEGER;
            case 'CALL_TYPE':
                return Schema::TYPE_INTEGER;
            case 'CRM_ACTIVITY_ID':
                return Schema::TYPE_INTEGER;
            case 'CRM_ENTITY_ID':
                return Schema::TYPE_INTEGER;
            case 'CALL_DURATION':
                return Schema::TYPE_INTEGER;
                
            default:
                return Schema::TYPE_STRING;
        }
    }

}
