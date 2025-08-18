<?php

namespace wm\admin\models\synchronization;

//

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\smartproces\SmartProcesSynchronizationFullListJob;
use wm\admin\jobs\smartproces\SmartProcesSynchronizationFullGetJob;
use wm\admin\jobs\smartproces\SmartProcesSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\Exception;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 *
 */
class SmartProces extends BaseEntity implements SynchronizationInterface
{
    /**
     * @var int
     */
    public static $entityTypeId = 0;

    /**
     * @var array|null Кэш полей с датами
     */
    private $_dateTimeFields;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_smartproces_0';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = SmartProcesSynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = SmartProcesSynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = SmartProcesSynchronizationFullGetJob::class;

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
            'crm.item.list',
            ['entityTypeId' => static::$entityTypeId]
        );
        return $request['total'];
    }

    /**
     * @return array|mixed|mixed[]
     */
    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        if (!$cache) {
            throw new Exception('Cache not found');
        }
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

    /**
     * @return array|mixed[]
     * @throws \Exception
     */
    public static function getB24FieldsList()
    {
        $result = [];
        foreach (self::getB24Fields() as $key => $value) {
            $result[$key] = ArrayHelper::getValue($value, 'formLabel') ?: ArrayHelper::getValue($value, 'title');
        }
        return $result;
    }

    /**
     * @param $modelAgentTimeSettings
     * @return void
     */
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

    /**
     * @return void
     */
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

//    /**
//     * @param $data
//     * @return void
//     */
//    public function loadData($data)
//    {
//        foreach ($data as $key => $val) {
//            if (in_array($key, array_keys($this->attributes))) {
//                is_array($val) ? $this->$key = json_encode($val) : $this->$key = $val;
//            }
//        }
//        $this->save();
//        if ($this->errors) {
//            Yii::error($this->errors, 'SmartProces->loadData()');
//        }
//    }

    /**
     * @param $data
     * @return void
     */
    public function loadData($data)
    {
        foreach ($data as $key => $val) {
            if (array_key_exists($key, $this->attributes)) {
                if (is_array($val)) {
                    $this->$key = json_encode($val);
                } elseif (in_array($key, $this->getDateTimeFields())) {
                    $this->$key = $this->convertDateTimeFormat($val);
                } else {
                    $this->$key = $val;
                }
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'SmartProces->loadData()');
        }
    }

    /**
     * @param $method
     * @param $dateTimeStart
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
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

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function synchronization()
    {
        $id = Yii::$app->queue->push(
            Yii::createObject( //
                [
                    'class' => static::$synchronizationDeltaJob,
                    'modelClass' => static::class,
                    'entityTypeId' => static::$entityTypeId
                ]
            )
        );
        return $id;
    }

    /**
     * Возвращает список полей, которые содержат даты
     * @return array
     */
    public function getDateTimeFields()
    {
        if ($this->_dateTimeFields === null) {
            $this->_dateTimeFields = [];
            foreach ($this->attributes as $field => $value) {
                // Определяем поля с датами по названию (содержит 'Time' или 'Date')
                if (preg_match('/(Time|Date|Dt)$/i', $field)) {
                    $this->_dateTimeFields[] = $field;
                }
            }
        }
        return $this->_dateTimeFields;
    }

    /**
     * Конвертирует формат даты в MySQL-совместимый
     * @param string|null $dateTime
     * @return string|null
     */
    protected function convertDateTimeFormat($dateTime)
    {
        if (empty($dateTime) || $dateTime === '0000-00-00 00:00:00') {
            return null;
        }

        // Если дата уже в правильном формате
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dateTime)) {
            return $dateTime;
        }

        try {
            // Пробуем разные форматы дат
            if (strpos($dateTime, 'T') !== false) {
                // ISO 8601 формат (2025-06-19T17:44:31+03:00)
                return (new \DateTime($dateTime))->format('Y-m-d H:i:s');
            } elseif (is_numeric($dateTime)) {
                // Unix timestamp
                return date('Y-m-d H:i:s', $dateTime);
            } else {
                // Другие форматы
                return (new \DateTime($dateTime))->format('Y-m-d H:i:s');
            }
        } catch (\Exception $e) {
            Yii::error("Failed to convert datetime: $dateTime. Error: " . $e->getMessage(), 'SmartProces->convertDateTimeFormat()');
            return null;
        }
    }

}
