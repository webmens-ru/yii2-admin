<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\task\TaskSynchronizationFullListJob;
use wm\admin\jobs\task\TaskSynchronizationFullGetJob;
use wm\admin\jobs\task\TaskSynchronizationDeltaJob;
use wm\admin\jobs\task\TaskSynchronizationDiffJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\Exception;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 *
 */
class Task extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_task';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = TaskSynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = TaskSynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = TaskSynchronizationFullGetJob::class;

    /**
     * @var string
     */
    public static $synchronizationDiffJob = TaskSynchronizationDiffJob::class;

    /**
     * @var string
     */
    public static $primaryKeyColumnName = 'id';


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
            'tasks.task.list',
            ['select' => ['ID']]
        );
        return $request['total'];
    }

    /**
     * @return mixed[]
     */
    public static function getB24Fields()
    {
        $arr = [];
        $cache = Yii::$app->cache;
        if (!$cache) {
            throw new Exception('Cache not found');
        }
        $key = 'tasks.task.getFields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'tasks.task.getFields'
            ), 'result.fields');
            return $data;
        }, 300);
//        Yii::warning($fields, '$fields');
        foreach ($fields as $key => $val) {
            $arr[Inflector::variablize(strtolower($key))] = $val;
        }
//        Yii::warning($arr, '$arr');
        return $arr;
    }

    /**
     * @return mixed[]
     * @throws \Exception
     */
    public static function getB24FieldsList()
    {
        $result = [];
        foreach (self::getB24Fields() as $key => $value) {
            if (ArrayHelper::getValue($value, 'formLabel')) {
                $result[$key] = ArrayHelper::getValue($value, 'formLabel');
            } elseif (ArrayHelper::getValue($value, 'title')) {
                $result[$key] = ArrayHelper::getValue($value, 'title');
            } else {
                $result[$key] = $key;
            }
        }
        return $result;
    }

    /**
     * @param mixed $modelAgentTimeSettings
     * @return void
     */
    public static function startSynchronization($modelAgentTimeSettings)
    {
        $events = ['OnTaskAdd', 'OnTaskUpdate', 'OnTaskDelete'];
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
            $agent->name = 'Синхронизация дельты задачи';
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
    public static function stopSynchronization()
    {
        $events = ['OnTaskAdd', 'OnTaskUpdate', 'OnTaskDelete'];
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
     * @param mixed[] $oneEntity
     * @return void
     */
    public function loadData($oneEntity)
    {
        $attributes = array_keys($this->attributes);
        foreach ($oneEntity as $key => $val) {
            if (in_array($key, array_keys($this->attributes))) {
                $data = '';
                if (is_array($val)) {
                    $data = json_encode($val);
                } else {
                    $data = $val;
                }
                if (strlen($data) > 255) {
                    $this->$key = substr($data, 0, 255);
                } else {
                    $this->$key = $data;
                }
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'Task->loadData()');
        }
    }
}
