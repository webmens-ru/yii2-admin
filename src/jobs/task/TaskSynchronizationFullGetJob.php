<?php

namespace wm\admin\jobs\task;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;


class TaskSynchronizationFullGetJob extends BaseObject implements \yii\queue\JobInterface
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
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
        $request = $obB24->client->call('tasks.task.list');
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $res = $request['result']['tasks'];
        for ($i = 1; $i < $countCalls; $i++) {
            $obB24->client->addBatchCall(
                'tasks.task.list',
                [
                    'start' => $obB24->client::MAX_BATCH_CALLS * $i
                ],
                function ($result) use (&$res) {
                    $res = array_merge($res, $result['result']['tasks']);
                }
            );
        }
        $obB24->client->processBatchCalls();
        return ArrayHelper::getColumn($res, 'id');


//        $component = new b24Tools();
//        $b24App = $component->connectFromAdmin();
//        $obB24 = new B24Object($b24App);
//        $params = [
//            'order' => ["id" => "ASC"],
//            'select' => ['id'],
//            'filter' => [
//                '>id' => 0
//            ]
//        ];
//        $request = $obB24->client->call('tasks.task.list', $params);
//        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
//        $countBatchCalls = (int)ceil($countCalls / 50);
//        $res = [];
//        for ($j = 0; $j < $countBatchCalls; $j++) {
//            if ($j == 0) {
//                $prevId = 0;
//            } else {
//                $prevId = max(ArrayHelper::getColumn($res, 'id'));
//            }
//            for ($i = 0; $i < 50; $i++) {
//                if ($countCalls > 0) {
//                    $idx = $obB24->client->addBatchCall('tasks.task.list',
//                        [
//                            'order' => ["id" => "ASC"],
//                            'filter' => [
//                                '>id' => $prevId
//                            ],
//                            'select' => ['id'],
//                            'start' => -1
//                        ],
//                        function ($result) use (&$res) {
//                            $res = array_merge($res, $result['result.tasks']);
//                        }
//                    );
//                }
//                $countCalls--;
//                $prevId = '$result[' . $idx . '][tasks][49][id]';
//            }
//            $obB24->client->processBatchCalls();
//        }
//        return ArrayHelper::getColumn($res, 'id');
    }

    public function getB24Get($arrayId)
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        foreach ($arrayId as $id) {
//            try{}catch ()//TODO
            $obB24->client->addBatchCall('tasks.task.get',
                ['id' => $id],
                function ($result) {
                    $data = ArrayHelper::getValue($result, 'result.task');
                    $id = ArrayHelper::getValue($data, 'id');
                    $model = $this->modelClass::find()->where(['id' => $id])->one();
                    if (!$model) $model = new $this->modelClass();
                    $model->loadData($data);
                }
            );
        }
        $obB24->client->processBatchCalls();
        return true;
    }
}