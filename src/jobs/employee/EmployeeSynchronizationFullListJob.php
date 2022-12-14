<?php

namespace wm\admin\jobs\employee;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;


class EmployeeSynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {
        $this->modelClass::deleteAll();
        $arrayId = $this->getIdsBatch();
        if ($arrayId) {
            $this->getB24Get($arrayId);
        }
    }

    public function getIdsBatch()
    {
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
        $request = $obB24->client->call('user.get');
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $res = $request['result'];
        for ($i = 1; $i < $countCalls; $i++) {
            $obB24->client->addBatchCall(
                'user.get',
                [
                    'start' => $obB24->client::MAX_BATCH_CALLS * $i
                ],
                function ($result) use (&$res) {
                    $res = array_merge($res, $result['result']);
                }
            );
        }
        $obB24->client->processBatchCalls();
        return ArrayHelper::getColumn($res, 'ID');
    }

    public function getB24Get($arrayId)
    {
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        foreach ($arrayId as $id) {
//            try{}catch ()//TODO
            $obB24->client->addBatchCall('user.get',
                ['ID' => $id],
                function ($result) {
                    $data = ArrayHelper::getValue($result, 'result')[0];
                    $id = ArrayHelper::getValue($data, 'ID');
                    $model = $this->modelClass::find()->where(['ID' => $id])->one();
                    if (!$model) $model = new $this->modelClass();
                    $model->loadData($data);
                }
            );
        }
        $obB24->client->processBatchCalls();
        return true;
    }
}