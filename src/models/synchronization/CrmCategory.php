<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\crmcategory\CrmCategorySynchronizationFullListJob;
use wm\admin\jobs\crmcategory\CrmCategorySynchronizationFullGetJob;
use wm\admin\jobs\crmcategory\CrmCategorySynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class CrmCategory extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_crm_category';
    }

    public static $synchronizationFullListJob = CrmCategorySynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = CrmCategorySynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = CrmCategorySynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'id';


    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'crm.category.list',
            ['entityTypeId' => 2]// направления сделок
        );
        return $request['total'];
    }

    public static function getB24Fields()
    {
        $arr =[];
        $cache = Yii::$app->cache;
        $key = 'crm.category.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'crm.category.fields',
                [
                    'entityTypeId' => 2
                ]
            ), 'result.fields');
            return $data;
        }, 300);
        foreach($fields as $key => $val){
            $arr[Inflector::variablize(strtolower($key))] = $val;
        }
        return $arr;
    }

    public static function getB24FieldsList()
    {
        $result = [];
        foreach (self::getB24Fields() as $key => $value) {
            $result[$key] = ArrayHelper::getValue($value, 'formLabel') ?: ArrayHelper::getValue($value, 'title');
        }
        return $result;
    }

    public static function startSynchronization($period)
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты сделки';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 00:00:00';
        }
        $agent->period = $period;
        $agent->status_id = 1;
        $agent->save();
    }

    public static function stopSynchronization()
    {
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
            Yii::error($this->errors, 'CrmCategory->loadData()');
        }
    }
}
