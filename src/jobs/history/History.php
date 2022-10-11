<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\history\HistorySynchronizationFullListJob;
use wm\admin\jobs\history\HistorySynchronizationFullGetJob;
use wm\admin\jobs\history\HistorySynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class History extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_history';
    }

    public static $synchronizationFullListJob = HistorySynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = HistorySynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = HistorySynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'ID';


    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'crm.stagehistory.list',
            [
                'entityTypeId' => 2,
                'select' => ['ID']
            ]
        );
        return $request['total'];//
    }

    public static function getB24Fields()
    {
        return [
            "ID" => "Идентификатор записи",
            "TYPE_ID" => "Тип записи. Может принимать значения: 1 - создание сущности, 2 - перевод на промежуточную стадию, 3 - перевод на финальную стадию",
            "OWNER_ID" => "Идентификатор сущности, в которой изменилась стадия",
            "CREATED_TIME" => "Дата и время попадания на стадию",
            "CATEGORY_ID" => "Идентификатор направления (воронки)",
            "STAGE_SEMANTIC_ID" => "Семантика статуса P - промежуточная стадия, S - успешная стадия, F - провальная стадия (стадии)",
            "STAGE_ID" => "Идентификатор стадии"
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
            $agent->name = 'Синхронизация дельты история';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 00:00:00';
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
            Yii::error($this->errors, 'History->loadData()');
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
            case 'TYPE_ID':
                return Schema::TYPE_INTEGER;
            case 'OWNER_ID':
                return Schema::TYPE_INTEGER;
            case 'CATEGORY_ID':
                return Schema::TYPE_INTEGER;
            case 'STAGE_SEMANTIC_ID':
                return Schema::TYPE_STRING . '(8)';
            case 'CREATED_TIME':
                return Schema::TYPE_TIME;

            default:
                return Schema::TYPE_STRING;
        }
    }
}
