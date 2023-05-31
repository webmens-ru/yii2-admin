<?php

namespace wm\admin\jobs\taskElapsedItem;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\b24tools\b24Tools;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\queue\JobInterface;
use wm\admin\jobs\taskElapsedItem\TaskElapsedItemSynchronizationFullJob as FullJob;

class TaskElapsedItemSynchronizationFullListJob extends BaseObject implements JobInterface
{
    public $modelClass;


    public function execute($queue) //TODO до 50-ти сотрудников
    {

        $this->modelClass::deleteAll(/*['>=', 'CREATED_DATE' => date('Y-m-d', strtotime('-30 days'))]*/);
        $modelTask = Yii::createObject($this->modelClass);
        $fieldsTask = $modelTask->attributes();
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result';
        $params = [
//            'select' => $fieldsTask,
        ];
        $request = $b24Obj->client->call(
            'task.elapseditem.getlist',
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
                    'task.elapseditem.getlist',
                    array_merge(
                        $params,
                        [
                            'PARAMS' => [
                                'nPageSize' => 50,
                                'iNumPage' => $i
                            ]
                        ]
                    ),
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
//        foreach ($this->userIds as $userId) {
//            $listDataSelector = 'result';
//            $params = [
//                'type' => 'user',
//                'ownerId' => $userId,
//                'from' => $this->from,
//            ];
//            $request = $b24Obj->client->call(
//                'calendar.event.get',
//                $params
//            );
//            foreach (ArrayHelper::getValue($request, $listDataSelector) as $oneEntity) {
//                $model = $this->modelClass::find()->where(['ID' => $oneEntity['ID']])->one();
//                if (!$model) {
//                    $model = Yii::createObject($this->modelClass);
//                }
//                $model->loadData($oneEntity);
//            }
//        }
    }
}
