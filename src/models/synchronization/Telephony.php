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

/**
 *
 */
class Telephony extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_telephony';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = TelephonySynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = TelephonySynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = TelephonySynchronizationFullGetJob::class;

    /**
     * @var string
     */
    public static $primaryKeyColumnName = 'ID';

    /**
     * @return mixed
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
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

    /**
     * @return mixed[]
     */
    public static function getB24Fields()
    {
        return [
            'ID' => ['id' => "ID", 'title' => 'Идентификатор звонка (для внутренних целей)'],
            'PORTAL_USER_ID' => [
                'id' => "PORTAL_USER_ID",
                'title' => 'Идентификатор ответившего 2 - Вх или позвонившего 1 - Исх'
            ],
            'PORTAL_NUMBER' => [
                'id' => "PORTAL_NUMBER",
                'title' => 'Номер, на который поступил звонок 2 - Вх или с которого 1 - Исх'
            ],
            'PHONE_NUMBER' => [
                'id' => "PHONE_NUMBER",
                'title' => 'Номер, с которого звонит абонент 2 - Вх или номер, которому звонит оператор 1 - Исх'
            ],
            'CALL_ID' => ['id' => "CALL_ID", 'title' => 'Идентификатор звонка'],
            'EXTERNAL_CALL_ID' => ['id' => "EXTERNAL_CALL_ID", 'title' => 'EXTERNAL_CALL_ID'],
            'CALL_CATEGORY' => ['id' => "CALL_CATEGORY", 'title' => 'CALL_CATEGORY'],
            'CALL_DURATION' => ['id' => "CALL_DURATION", 'title' => 'Продолжительность звонка в секундах'],
            'CALL_START_DATE' => ['id' => "CALL_START_DATE", 'title' => 'Время инициализации звонка'],
            'CALL_RECORD_URL' => ['id' => "CALL_RECORD_URL", 'title' => 'CALL_RECORD_URL'],
            'CALL_VOTE' => [
                'id' => "CALL_VOTE",
                'title' => 'Оценка звонка используется только для внутренней телефонии'
            ],
            'COST' => ['id' => "COST", 'title' => 'Стоимость звонка'],
            'COST_CURRENCY' => ['id' => "COST_CURRENCY", 'title' => 'Валюта звонка (RUR, USD, EUR)'],
            'CALL_FAILED_CODE' => ['id' => "CALL_FAILED_CODE", 'title' => 'Код вызова'],
            'CALL_FAILED_REASON' => [
                'id' => "CALL_FAILED_REASON",
                'title' => 'Текстовое описание кода вызова (латиница)'
            ],
            'CRM_ENTITY_TYPE' => [
                'id' => "CRM_ENTITY_TYPE",
                'title' => 'Тип объекта CRM, к которому прикреплено дело, например: LEAD'
            ],
            'CRM_ENTITY_ID' => [
                'id' => "CRM_ENTITY_ID",
                'title' => 'Идентификатор объекта CRM, к которому прикреплено дело'
            ],
            'CRM_ACTIVITY_ID' => [
                'id' => "CRM_ACTIVITY_ID",
                'title' => 'Идентификатор дела CRM, созданного на основании звонка'
            ],
            'REST_APP_ID' => [
                'id' => "REST_APP_ID",
                'title' => 'Идентификатор приложения интеграции внешней телефонии'
            ],
            'REST_APP_NAME' => [
                'id' => "REST_APP_NAME",
                'title' => 'Название приложения интеграции внешней телефонии'
            ],
            'TRANSCRIPT_ID' => [
                'id' => "TRANSCRIPT_ID",
                'title' => 'Идентификатор расшифровки звонка'
            ],
            'TRANSCRIPT_PENDING' => [
                'id' => "TRANSCRIPT_PENDING",
                'title' => 'Y\N. Признак того, что расшифровка будет получена позднее'
            ],
            'SESSION_ID' => [
                'id' => "SESSION_ID",
                'title' => 'Идентификатор сессии звонка на стороне Voximplant'
            ],
            'REDIAL_ATTEMPT' => [
                'id' => "REDIAL_ATTEMPT",
                'title' => 'Номер попытки дозвониться (для обратных звонков)'
            ],
            'COMMENT' => ['id' => "COMMENT", 'title' => 'Комментарий к звонку'],
            'RECORD_FILE_ID' => ['id' => "RECORD_FILE_ID", 'title' => 'Идентификатор файла с записью звонка'],
            'CALL_TYPE' => ['id' => "CALL_TYPE", 'title' => 'Тип вызова'],
        ];
    }

    /**
     * @return mixed[]
     */
    public static function getB24FieldsList()
    {
        return ArrayHelper::map(self::getB24Fields(), 'id', 'title');
    }

    /**
     * @param mixed $modelAgentTimeSettings
     * @return void
     */
    public static function startSynchronization($modelAgentTimeSettings)
    {
        $events = ['OnVoximplantCallEnd'];
        foreach ($events as $eventName) {
            $event = Events::find()->where(['event_name' => $eventName, 'event_type' => 'offline'])->one();
            if (!$event) {
                $event = new Events();
                $event->event_name = $eventName;
                $event->handler = '_';
                $event->event_type = 'offline';
                $event->save();
            }
            if (!$event->isInstallToB24()) {
                $event->toBitrix24();
            }
        }

        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты телефония';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 03:00:00';
        }
        $agent->load(ArrayHelper::toArray($modelAgentTimeSettings), '');
        $agent->status_id = 1;
        $agent->save();
    }

    /**
     * @return void
     */
    public static function stopSynchronization()
    {
        $events = ['OnVoximplantCallEnd'];
        foreach ($events as $eventName) {
            $event = Events::find()->where(['event_name' => $eventName, 'event_type' => 'offline'])->one();
            if (!$event) {
                $event = new Events();
                $event->event_name = $eventName;
                $event->handler = '_';
                $event->event_type = 'offline';
                $event->save();
            }
            if ($event->isInstallToB24()) {
                $event->removeBitrix24();
            }
        }

        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if ($agent) {
            $agent->status_id = 0;
            $agent->save();
        }
    }

    /**
     * @param mixed[] $data
     * @return void
     */
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

    /**
     * @param mixed[] $addFieldNames
     * @return true
     * @throws \yii\db\Exception
     */
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

    /**
     * @param string $lable
     * @return string
     */
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
