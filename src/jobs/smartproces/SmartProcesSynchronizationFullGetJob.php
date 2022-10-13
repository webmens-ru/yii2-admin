<?php

namespace wm\admin\jobs\smartproces;//

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;


class SmartProcesSynchronizationFullGetJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;
    public $entityTypeId;

    public function execute($queue)
    {
//        Yii::warning(800, '800');
        $this->modelClass::deleteAll();
        $arrayId = $this->getIdsBatch();
        if ($arrayId) {
            $this->getB24Get($arrayId);
        }
//        Yii::warning(700, '700');
    }

    public function getIdsBatch()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
        $params = [
            'entityTypeId' => $this->entityTypeId,
            'order' => ["id" => "ASC"],
            'select' => ['id'],
//            'filter' => [
//                '>id' => 0
//            ]
        ];
        $request = $obB24->client->call('crm.item.list', $params);
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $countBatchCalls = (int)ceil($countCalls / 50);
        $res = [];
//        Yii::warning(900, '900');
        for ($j = 0; $j < $countBatchCalls; $j++) {

            if ($j == 0) {
                $prevId = 0;
            } else {
                $prevId = max(ArrayHelper::getColumn($res, 'ID'));
            }
            for ($i = 0; $i < 50; $i++) {
                if ($countCalls > 0) {
                    $idx = $obB24->client->addBatchCall('crm.item.list',
                        [
                            'entityTypeId' => $this->entityTypeId,
                            'order' => ["id" => "ASC"],
                            'filter' => [
                                '>id' => $prevId
                            ],
                            'select' => ['id'],
                            'start' => -1
                        ],
                        function ($result) use (&$res) {
                            $res = array_merge($res, $result['result']['items']);
                        }
                    );
                }
                $countCalls--;
                $prevId = '$result[' . $idx . '][items][49][id]';
            }
            $obB24->client->processBatchCalls();
        }
        return ArrayHelper::getColumn($res, 'id');
    }

    public function getB24Get($arrayId)
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        foreach ($arrayId as $id) {
//            try{}catch ()//TODO
            $obB24->client->addBatchCall('crm.item.get',
                [
                    'id' => $id,
                    'entityTypeId' => $this->entityTypeId,
                ],
                function ($result) {
                    $data = ArrayHelper::getValue($result, 'result.item');
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