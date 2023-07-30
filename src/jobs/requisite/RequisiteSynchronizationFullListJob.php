<?php

namespace wm\admin\jobs\requisite;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class RequisiteSynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {

        $this->modelClass::deleteAll();
        $modelRequisite = Yii::createObject($this->modelClass);
        $fieldsRequisite = $modelRequisite->attributes();
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result';
        $params = [
            'select' => $fieldsRequisite,
        ];
        $request = $b24Obj->client->call(
            'crm.requisite.list',
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
                    'crm.requisite.list',
                    array_merge($params, ['start' => $b24Obj->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use ($listDataSelector) {
                        Yii::$app->db->close();
                        Yii::$app->db->open();
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
