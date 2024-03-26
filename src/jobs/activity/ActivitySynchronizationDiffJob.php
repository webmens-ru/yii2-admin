<?php

namespace wm\admin\jobs\activity;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class ActivitySynchronizationDiffJob extends BaseObject implements \yii\queue\JobInterface
{

    /**
     * @var string
     */
    public $modelClass;

    public $period = [
        60, // 1
//        300, // 5 минут
//        600, // 10 минут
//        3600, // 1 час
        86400, // 1 день
        604800, // 1 неделя
        2592000, // 30 дней
        7776000, // 90 day
        15552000, // 180 day
        23328000, // 270 day
        31536000, // 365  дней
    ];

    public function execute($queue)
    {
        $curentDate = strtotime('now') + 10800; // TODO
        Yii::warning(date('Y-m-d H:i:s', $curentDate), '$curentDate_1');
        $this->syncCount($curentDate);
        $this->syncUpdate($curentDate);
    }

    public function syncCount($curentDate){
        for($i = 0; $i < count($this->period); $i++){
            $startDate = $curentDate - $this->period[$i];
            $b24Count = $this->getB24Count(['<CREATED' => date('Y-m-d H:i:s', $startDate) ]);
            $dbCount = $this->getDbCount(['<', 'CREATED', date('Y-m-d H:i:s', $startDate)]);
            Yii::warning([$i, $b24Count, $dbCount, date('Y-m-d H:i:s', $startDate)], 'syncCount_$b24Count');
            if($b24Count == $dbCount && $i == 0){
                break;
            }
            if($b24Count == $dbCount){
                $this->addTaskToQueue();
                $filterB24 = ['>=CREATED' => date('Y-m-d H:i:s', $startDate)];
                $filterDb = ['>=', 'CREATED', date('Y-m-d H:i:s', $startDate)];
                $this->startSync($filterB24, $filterDb);
                break;
            }
            if($i == count($this->period)-1 && $b24Count != $dbCount){
                $this->addTaskToQueue();
                $this->startSync();
                break;
            }
        }
    }

    public function syncUpdate($curentDate){
        for($i = 0; $i < count($this->period); $i++){
            $startDate = $curentDate - $this->period[$i];
            $b24Count = $this->getB24Count(['<LAST_UPDATED' => date('Y-m-d H:i:s', $startDate)]);
            $dbCount = $this->getDbCount(['<', 'LAST_UPDATED', date('Y-m-d H:i:s', $startDate)]);
            Yii::warning([$i, $b24Count, $dbCount, date('Y-m-d H:i:s', $startDate)], 'syncUpdate_$b24Count');
            if($b24Count == $dbCount && $i == 0){
                break;
            }
            if($b24Count == $dbCount){
                $this->addTaskToQueue();
                $filterB24 = ['>=LAST_UPDATED' => date('Y-m-d H:i:s', $startDate)];
                $filterDb = ['>=', 'LAST_UPDATED', date('Y-m-d H:i:s', $startDate)];
                $this->startSync($filterB24, $filterDb);
                break;
            }
            if($i == count($this->period)-1 && $b24Count != $dbCount){
                $this->addTaskToQueue();
                $this->startSync();
                break;
            }
        }
    }

    public function getB24Count($filter){
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $list = $obB24->client->call(
            "crm.activity.list",
            [
                "select" => ["ID"],
                "filter" => $filter,
            ]);
        return $list['total'];

    }

    public function getDbCount($filter){
        $count = $this->modelClass::find()->where($filter)->count();
        return $count;
    }

    public function startSync($filterB24 = null,  $filterDb = null){
        $filter = [];
        if($filterDb){
            $this->modelClass::deleteAll($filterDb);
            $filter = $filterB24;
        }
        else{
            $this->modelClass::deleteAll();
        }
        $modelActivity = Yii::createObject($this->modelClass);
        $fieldsActivity = $modelActivity->attributes();
        if($logPathQueue = ArrayHelper::getValue(Yii::$app->params, 'logPathQueue')){
            Yii::$app->params['logPath'] = $logPathQueue;
        }
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result';
        $params = [
            'select' => $fieldsActivity,
            'filter' => $filter,
            'order' => ["ID"=> "ASC"]
        ];
        $request = $b24Obj->client->call(
            'crm.activity.list',
            $params
        );
        foreach (ArrayHelper::getValue($request, $listDataSelector) as $oneEntity) {
            $model = Yii::createObject($this->modelClass);
            $model->loadData($oneEntity);
        }
        $countCalls = (int)ceil($request['total'] / $b24Obj->client::MAX_BATCH_CALLS);
        $data = ArrayHelper::getValue($request, $listDataSelector);
        if (count($data) != $request['total']) {
            for ($i = 1; $i < $countCalls; $i++) {
                $b24Obj->client->addBatchCall(
                    'crm.activity.list',
                    array_merge($params, ['start' => $b24Obj->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use ($listDataSelector) {
                        foreach (ArrayHelper::getValue($result, $listDataSelector) as $oneEntity) {
                            $model = $this->modelClass::find()->where(['ID' => $oneEntity['ID']])->one();
                            if (!$model) {
                                $model = Yii::createObject($this->modelClass);
                            }
                            $model->loadData($oneEntity);
                        }
                    }
                );
            }
            $b24Obj->client->processBatchCalls();
        }
    }

    public function addTaskToQueue(){
        $id = Yii::$app->queue->push(
            Yii::createObject(
                [
                    'class' => static::class,
                    'modelClass' => $this->modelClass
                ]
            )
        );
    }
    
    
}
