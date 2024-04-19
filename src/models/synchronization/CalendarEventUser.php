<?php

namespace wm\admin\models\synchronization;

//

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\calendarEventUser\CalendarEventUserSynchronizationFullListJob;
use wm\admin\jobs\calendarEventUser\CalendarEventUserSynchronizationDeltaJob;
use wm\admin\jobs\calendarEventUser\CalendarEventUserSynchronizationFullGetJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class CalendarEventUser extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_calendar_user';
    }

    public static $synchronizationDeltaJob = CalendarEventUserSynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = CalendarEventUserSynchronizationFullGetJob::class;

    public static $synchronizationFullListJob = CalendarEventUserSynchronizationFullListJob::class;

    public static $primaryKeyColumnName = 'ID';

    public static function getCountB24()
    {
        return 'Не измеряли';
    }

    public static function getB24Fields()
    {
        $fields = [
            'ACCESSIBILITY' => ['title' => 'ACCESSIBILITY', 'type' => 'string'],
            'ATTENDEE_LIST' => ['title' => 'ATTENDEE_LIST', 'type' => 'string', 'isMultiple' => true ],
            'CAL_DAV_LABEL' => ['title' => 'CAL_DAV_LABEL', 'type' => 'string', 'isMultiple' => true],
            'CAL_TYPE' => ['title' => 'CAL_TYPE', 'type' => 'string'],
            'COLOR' => ['title' => 'COLOR', 'type' => 'string'],
            'CREATED_BY' => ['title' => 'CREATED_BY', 'type' => 'integer'],
            'DATE_CREATE' => ['title' => 'DATE_CREATE', 'type' => 'datetime'],
            'DATE_FROM' => ['title' => 'DATE_FROM', 'type' => 'datetime'],
            'DATE_FROM_TS_UTC' => ['title' => 'DATE_FROM_TS_UTC', 'type' => 'integer'],
            'DATE_TO' => ['title' => 'DATE_TO', 'type' => 'datetime'],
            'DATE_TO_TS_UTC' => ['title' => 'DATE_TO_TS_UTC', 'type' => 'integer'],
            'DAV_EXCH_LABEL' => ['title' => 'DAV_EXCH_LABEL'],
            'DAV_XML_ID' => ['title' => 'DAV_XML_ID', 'type' => 'string'],
            'DELETED' => ['title' => 'DELETED', 'type' => 'string'],
            'DT_LENGTH' => ['title' => 'DT_LENGTH', 'type' => 'integer'],
            'DT_SKIP_TIME' => ['title' => 'DT_SKIP_TIME', 'type' => 'string'],
            'EVENT_TYPE' => ['title' => 'EVENT_TYPE'],
            'EXDATE' => ['title' => 'EXDATE'],
            'G_EVENT_ID' => ['title' => 'G_EVENT_ID'],
            'ID' => ['title' => 'ID', 'type' => 'integer'],
            'IMPORTANCE' => ['title' => 'IMPORTANCE', 'type' => 'string'],
            'IS_MEETING' => ['title' => 'IS_MEETING', 'type' => 'boolean'],
            'LOCATION' => ['title' => 'LOCATION'],
            'MEETING' => ['title' => 'MEETING'],
            'MEETING_HOST' => ['title' => 'MEETING_HOST'],
            'MEETING_STATUS' => ['title' => 'MEETING_STATUS'],
            'NAME' => ['title' => 'NAME'],
            'ORIGINAL_DATE_FROM' => ['title' => 'ORIGINAL_DATE_FROM'],
            'OWNER_ID' => ['title' => 'OWNER_ID', 'type' => 'integer'],
            'PARENT_ID' => ['title' => 'PARENT_ID', 'type' => 'integer'],
            'PRIVATE_EVENT' => ['title' => 'PRIVATE_EVENT'],
            'REMIND' => ['title' => 'REMIND'],
            'RRULE' => ['title' => 'RRULE'],
            'SECTION_ID' => ['title' => 'SECTION_ID'],
            'SECT_ID' => ['title' => 'SECT_ID'],
            'SYNC_STATUS' => ['title' => 'SYNC_STATUS'],
            'TIMESTAMP_X' => ['title' => 'TIMESTAMP_X', 'type' => 'datetime'],
            'TZ_FROM' => ['title' => 'TZ_FROM'],
            'TZ_OFFSET_FROM' => ['title' => 'TZ_OFFSET_FROM', 'type' => 'integer'],
            'TZ_OFFSET_TO' => ['title' => 'TZ_OFFSET_TO', 'type' => 'integer'],
            'TZ_TO' => ['title' => 'TZ_TO'],
            'UF_CRM_CAL_EVENT' => ['title' => 'UF_CRM_CAL_EVENT', 'type' => 'boolean'],
            'VERSION' => ['title' => 'VERSION', 'type' => 'boolean'],
        ];

        return $fields;
    }

    private static function getEmploeIds()
    {

            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'user.get',
                ['ACTIVE' => true]
            ), 'result');

        $ids = ArrayHelper::getColumn($data, 'ID');
        return $ids;
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
            'OnCalendarEntryAdd',
            'OnCalendarEntryUpdate',
            'OnCalendarEntryDelete',
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
            $agent->name = 'Синхронизация дельты событий календаря';
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
            'OnCalendarEntryAdd',
            'OnCalendarEntryUpdate',
            'OnCalendarEntryDelete',
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
                if ($key == 'DATE_FROM' || $key == 'DATE_TO' || $key == 'TIMESTAMP_X' || $key == 'DATE_CREATE') {
                    $val = date('Y-m-d H:i:s', strtotime($val));
                }
                is_array($val) ? $this->$key = json_encode($val) : $this->$key = $val;
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'CalendarEventUser->loadData()');
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
                        'userIds' => self::getEmploeIds(),
                        'from' => date('Y-m-d', strtotime("-1 year")),
                    ]
                );
                break;
            case 'get':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullGetJob,
                        'modelClass' => static::class,
                        'userIds' => self::getEmploeIds(),
                        'from' => date('Y-m-d', strtotime("-1 year")),
                    ]
                );
                break;
            default:
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class,
                        'userIds' => self::getEmploeIds(),
                        'from' => date('Y-m-d', strtotime("-1 year")),
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
                    'userIds' => self::getEmploeIds()
                ]
            )
        );
        return $id;
    }
}
