<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\deal\DealSynchronizationFullListJob;
use wm\admin\jobs\deal\DealSynchronizationFullGetJob;
use wm\admin\jobs\deal\DealSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\Exception;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 *
 */
class Deal extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_deal';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = DealSynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = DealSynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = DealSynchronizationFullGetJob::class;

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
            'crm.deal.list',
            ['select' => ['ID']]
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
        $key = 'crm.deal.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'crm.deal.fields'
            ), 'result');
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
            $agent->date_run = date('Y-m-d H:i:s');
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
     * @param $data
     * @return void
     */
    public function loadData($data)
    {
        foreach ($data as $key => $val) {
            if (in_array($key, array_keys($this->attributes))) {
                $data = '';
                if (is_array($val)) {
                    $data = json_encode($val);
                } else {
                    $data = $val;
                }
                if (strlen($data) > 255) {
                    $this->$key = substr($data, 0, 255);
                    Yii::error("$data >255", 'Deal->loadData()');
                } else {
                    $this->$key = $data;
                }
            }
        }

        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'Deal->loadData()');
        }
    }
}
