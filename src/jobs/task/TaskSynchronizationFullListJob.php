<?php

namespace wm\admin\jobs\task;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class TaskSynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {
        $this->modelClass::deleteAll();
        $modelTask = Yii::createObject($this->modelClass);

        $fields = $modelTask->attributes();
        $fieldsTask = [];
        foreach ($fields as $field) {
            $str = Inflector::camel2words($field);
            $str = preg_replace("/(\d+)/", " $1", $str);
            $str = str_replace(" ", "_", strtolower($str));
            $str = strtoupper($str);
            $fieldsTask[] = $str;
        }

//        $fieldsTask = $modelTask->attributes();
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result.tasks';
        $params = [
            'order' => ["id" => "ASC"],
            'select' => $fieldsTask,
            'filter' => [
                '>id' => 0
            ],
        ];
        $request = $b24Obj->client->call(
            'tasks.task.list',
            $params
        );

        $countCalls = (int)ceil($request['total'] / $b24Obj->client::MAX_BATCH_CALLS);
        $countBatchCalls = (int)ceil($countCalls / 50);
        $maxId = 0;
        for ($j = 0; $j < $countBatchCalls; $j++) {
            if ($j == 0) {
                $prevId = 0;
            } else {
                $prevId = $maxId;
            }
            for ($i = 0; $i < 50; $i++) {
                if ($countCalls > 0) {
                    $idx = $b24Obj->client->addBatchCall(
                        'tasks.task.list',
                        [
                            'order' => ["id" => "ASC"],
                            'filter' => [
                                '>id' => $prevId
                            ],
                            'select' => $fieldsTask,
                            'start' => -1
                        ],
                        function ($result) use (&$maxId) {
                            foreach (ArrayHelper::getValue($result, 'result.tasks') as $oneEntity) {
                                $maxId = $oneEntity['id'];
                                $model = Yii::createObject($this->modelClass);
                                $model->loadData($oneEntity);

                            }
                        }
                    );
                    $prevId = '$result[' . $idx . '][tasks][49][id]';
                }
                $countCalls--;
            }
            $b24Obj->client->processBatchCalls();
        }
    }
}
