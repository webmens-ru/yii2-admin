<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\task\TaskSynchronizationFullListJob;
use wm\admin\jobs\task\TaskSynchronizationFullGetJob;
use wm\admin\jobs\task\TaskSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class Task extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_task';
    }

    public static $synchronizationFullListJob = TaskSynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = TaskSynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = TaskSynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'id';


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

    public static function getB24Fields()
    {
        $arr = [];
        $cache = Yii::$app->cache;
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

    public function loadData($data)
    {
        foreach ($data as $key => $val) {
            if (in_array($key, array_keys($this->attributes))) {
                if($val){
                    is_array($val) ? $this->$key = json_encode($val) : $this->$key = $val;
                }else{
                    $this->$key = null;
                }
            }else{
                if($val){
                    $this->$key = $val;
                }else{
                    $this->$key = null;
                }
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'Task->loadData()');
        }
    }
}
