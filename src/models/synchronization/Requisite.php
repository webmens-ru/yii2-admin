<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\requisite\RequisiteSynchronizationFullListJob;
use wm\admin\jobs\requisite\RequisiteSynchronizationDeltaJob;
use wm\admin\jobs\requisite\RequisiteSynchronizationFullGetJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class Requisite extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_requisite';
    }

    public static $synchronizationFullListJob = RequisiteSynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = RequisiteSynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = RequisiteSynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'ID';


    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'crm.requisite.list',
            ['select' => ['ID']]
        );
        return $request['total'];
    }

    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        $key = 'crm.requisite.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'crm.requisite.fields'
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
        $events = ['onCrmRequisiteAdd', 'onCrmRequisiteUpdate', 'onCrmRequisiteDelete'];
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
            $agent->name = 'Синхронизация дельты реквизитов';
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
        $events = ['onCrmRequisiteAdd', 'onCrmRequisiteUpdate', 'onCrmRequisiteDelete'];
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
                is_array($val) ? $this->$key = json_encode($val) : $this->$key = $val;
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'Requisite->loadData()');
        }
    }
}
