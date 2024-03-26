<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\activity\ActivitySynchronizationFullListJob;
use wm\admin\jobs\activity\ActivitySynchronizationFullGetJob;
use wm\admin\jobs\activity\ActivitySynchronizationDeltaJob;
use wm\admin\jobs\activity\ActivitySynchronizationDiffJob;

use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class Activity extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_activity';
    }

    public static $synchronizationFullListJob = ActivitySynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = ActivitySynchronizationDeltaJob::class;

    public static $synchronizationDiffJob = ActivitySynchronizationDiffJob::class;

    public static $synchronizationFullGetJob = ActivitySynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'ID';

    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'crm.activity.list',
            ['select' => ['ID']]
        );
        return $request['total'];
    }

    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        $key = 'crm.activity.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'crm.activity.fields'
            ), 'result');
            return $data;
        }, 300);
        return $fields;
    }

    public static function getB24FieldsList()
    {
        $result = [];
        foreach (self::getB24Fields() as $key => $value) {
            $result[$key] = ArrayHelper::getValue($value, 'formLabel') ?: ArrayHelper::getValue($value, 'title');
        }
        return $result;
    }

    public static function startSynchronization($modelAgentTimeSettings)
    {
        $events = ['onCrmActivityAdd', 'onCrmActivityUpdate', 'onCrmActivityDelete'];
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
            $agent->name = 'Синхронизация дельты дел';
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
        $events = ['onCrmActivityAdd', 'onCrmActivityUpdate', 'onCrmActivityDelete'];
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

    public function loadData($oneEntity)
    {
            foreach ($oneEntity as $key => $val) {
                if (in_array($key, array_keys($this->attributes))) {
                    $data = '';
                    if(is_array($val)){
                        $data = json_encode($val);
                    }else{
                        $data = $val;
                    }
                    if(strlen($data)>255){
                        $this->$key = substr($data, 0, 255);
                    }else{
                        $this->$key = $data;
                    }
                }
            }
            $this->save();
            if ($this->errors) {
                Yii::error($this->errors, 'Activity->loadData()');
            }
    }
}
