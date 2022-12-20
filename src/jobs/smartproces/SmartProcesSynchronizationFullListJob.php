<?php

namespace wm\admin\jobs\smartproces;

//

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class SmartProcesSynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;
    public $entityTypeId;

    public function execute($queue)
    {
        $this->modelClass::deleteAll();
        $modelSmartProces = Yii::createObject($this->modelClass);
        $fieldsSmartProces = $modelSmartProces->attributes();
//        Yii::warning($fieldsSmartProces, '$fieldsSmartProces');
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result.items';
        $params = [
            'select' => $fieldsSmartProces,
            'entityTypeId' => $this->entityTypeId,
        ];
        $request = $b24Obj->client->call(
            'crm.item.list',
            $params
        );
//        Yii::warning($request, '$request');
        foreach (ArrayHelper::getValue($request, $listDataSelector) as $oneEntity) {
            $model = Yii::createObject($this->modelClass);
            $model->loadData($oneEntity);
        }
        $countCalls = (int)ceil($request['total'] / $b24Obj->client::MAX_BATCH_CALLS);
        $data = ArrayHelper::getValue($request, $listDataSelector);
        if (count($data) != $request['total']) {
            for ($i = 1; $i < $countCalls; $i++) {
                $b24Obj->client->addBatchCall(
                    'crm.item.list',
                    array_merge($params, ['start' => $b24Obj->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use ($listDataSelector) {
                        foreach (ArrayHelper::getValue($result, $listDataSelector) as $oneEntity) {
                            $model = Yii::createObject($this->modelClass);
                            $model->loadData($oneEntity);
                        }
                    }
                );
            }
            $b24Obj->client->processBatchCalls();
        }
    }
}
