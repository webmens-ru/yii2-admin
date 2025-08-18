<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\historyDeal\HistorySynchronizationFullListJob;
use wm\admin\jobs\historyDeal\HistorySynchronizationFullGetJob;
use wm\admin\jobs\historyDeal\HistorySynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 *
 */
class HistoryDeal extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_history_deal';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = HistorySynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = HistorySynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = HistorySynchronizationFullGetJob::class;

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
            'crm.stagehistory.list',
            [
                'entityTypeId' => 2,
                'select' => ['ID']
            ]
        );
        return $request['total'];//
    }

    /**
     * @return string[]
     */
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

    /**
     * @return string[]
     */
    public static function getB24FieldsList()
    {
        return self::getB24Fields();
    }

    /**
     * @param $modelAgentTimeSettings
     * @return void
     */
    public static function startSynchronization($modelAgentTimeSettings)
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты история';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 01:01:01';
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
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if ($agent) {
            $agent->status_id = 0;
            $agent->save();
        }
    }

    /**
     * @param $data
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
            Yii::error($this->errors, 'History->loadData()');
        }
    }

    /**
     * @param string[] $addFieldNames
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
