<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\smartproces\SmartProcesSynchronizationFullListJob;
use wm\admin\jobs\smartproces\SmartProcesSynchronizationFullGetJob;
use wm\admin\jobs\smartproces\SmartProcesSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class SmartProces extends BaseEntity implements SynchronizationInterface

{
    public static $entityTypeId = 0;

    public static function tableName()
    {
        return 'sync_smartproces_0';
    }

    public static $synchronizationFullListJob = SmartProcesSynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = SmartProcesSynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = SmartProcesSynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'id';

    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'crm.item.list',
            ['entityTypeId' => static::$entityTypeId]
        );
        return $request['total'];
    }

    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        $key = 'crm.item.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'crm.item.fields',
                ['entityTypeId' => static::$entityTypeId]
            ), 'result.fields');
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
        $events = [
            'onCrmDynamicItemAdd_' . static::$entityTypeId,
            'onCrmDynamicItemUpdate_' . static::$entityTypeId,
            'onCrmDynamicItemDelete_' . static::$entityTypeId
        ];
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
            $agent->name = 'Синхронизация дельты смарт-процесса';
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
        $events = [
            'onCrmDynamicItemAdd_' . static::$entityTypeId,
            'onCrmDynamicItemUpdate_' . static::$entityTypeId,
            'onCrmDynamicItemDelete_' . static::$entityTypeId
        ];
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
            Yii::error($this->errors, 'SmartProces->loadData()');
        }
    }

    public static function addJobFull($method, $dateTimeStart = null)
    {
        $delay = 0;
        if ($dateTimeStart) {
            $diff = strtotime($dateTimeStart) - time();
            if ($diff > 0) {
                $delay = $diff;
            }
        }

        $objFullSync = null;

        switch ($method) {
            case 'list':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class,
                        'entityTypeId' => static::$entityTypeId
                    ]
                );
                break;
            case 'get':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullGetJob,
                        'modelClass' => static::class,
                        'entityTypeId' => static::$entityTypeId
                    ]
                );
                break;
            default:
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class,
                        'entityTypeId' => static::$entityTypeId
                    ]
                );
        }

        $id = Yii::$app->queue->delay($delay)->ttr(3600)->push($objFullSync);
        return $id;
    }

    public static function synchronization()
    {
        $id = Yii::$app->queue->push(
            Yii::createObject(
                [
                    'class' => static::$synchronizationDeltaJob,
                    'modelClass' => static::class,
                    'entityTypeId' => static::$entityTypeId
                ]
            )
        );
        return $id;
    }
}
