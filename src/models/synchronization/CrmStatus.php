<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\crmstatus\CrmStatusSynchronizationFullListJob;
use wm\admin\jobs\crmstatus\CrmStatusSynchronizationFullGetJob;
use wm\admin\jobs\crmstatus\CrmStatusSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 *
 */
class CrmStatus extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_crm_status';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = CrmStatusSynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = CrmStatusSynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = CrmStatusSynchronizationFullGetJob::class;

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
            'crm.status.list',
            //            ['select' => ['ID']]
        );
        return $request['total'];
    }

    /**
     * @return array|mixed|mixed[]
     */
    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        $key = 'crm.status.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'crm.status.fields'
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
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты справочников';
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
            Yii::error($this->errors, 'CrmStatus->loadData()');
        }
    }
}
