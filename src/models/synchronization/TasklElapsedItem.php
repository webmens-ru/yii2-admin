<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\taskElapsedItem\TaskElapsedItemSynchronizationFullListJob;
use wm\admin\jobs\taskElapsedItem\TaskElapsedItemSynchronizationFullGetJob;
use wm\admin\jobs\taskElapsedItem\TaskElapsedItemSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 *
 */
class TasklElapsedItem extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_task_elapsed_item';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = TaskElapsedItemSynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = TaskElapsedItemSynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = TaskElapsedItemSynchronizationFullGetJob::class;

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
            'task.elapseditem.getlist',
            []
        );
        return $request['total'];
    }

    /**
     * @return mixed[]
     */
    public static function getB24Fields()
    {
        $fields = [
            'ID' => ['title' => 'ID', 'type' => 'integer'],
            'TASK_ID' => ['title' => 'TASK_ID', 'type' => 'integer'],
            'USER_ID' => ['title' => 'USER_ID', 'type' => 'integer'],
            'COMMENT_TEXT' => ['title' => 'COMMENT_TEXT', 'type' => 'string'],
            'SECONDS' => ['title' => 'SECONDS', 'type' => 'integer'],
            'MINUTES' => ['title' => 'MINUTES', 'type' => 'integer'],
            'SOURCE' => ['title' => 'SOURCE', 'type' => 'string'],
            'CREATED_DATE' => ['title' => 'CREATED_DATE', 'type' => 'datetime'],
            'DATE_START' => ['title' => 'DATE_START', 'type' => 'datetime'],
            'DATE_STOP' => ['title' => 'DATE_STOP', 'type' => 'datetime'],
        ];

        return $fields;
    }

    /**
     * @return mixed[]
     * @throws \Exception
     */
    public static function getB24FieldsList()
    {
        $result = [];
        foreach (self::getB24Fields() as $key => $value) {
            $result[$key] = ArrayHelper::getValue($value, 'title');
        }
        return $result;
    }

    /**
     * @param mixed $modelAgentTimeSettings
     * @return void
     */
    public static function startSynchronization($modelAgentTimeSettings) //TODO
    {
        $events = ['onCrmDealAdd', 'onCrmDealUpdate', 'onCrmDealDelete'];
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
            $agent->name = 'Синхронизация дельты сделки';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 00:00:00';
        }
        $agent->load(ArrayHelper::toArray($modelAgentTimeSettings), '');
        $agent->status_id = 1;
        $agent->save();
    }

    /**
     * @return void
     */
    public static function stopSynchronization() //TODO
    {
        $events = ['onCrmDealAdd', 'onCrmDealUpdate', 'onCrmDealDelete'];
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
            Yii::error($this->errors, 'TasklElapsedItem->loadData()');
        }
    }
}
