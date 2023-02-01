<?php

namespace wm\admin\jobs\company;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class CompanySynchronizationFullGetJob extends BaseObject implements \yii\queue\JobInterface
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
        $params = [
            'order' => ["ID" => "ASC"],
            'select' => ['ID'],
            'filter' => [
                '>ID' => 0
            ]
        ];
        $request = $obB24->client->call('crm.company.list', $params);
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $countBatchCalls = (int)ceil($countCalls / 50);
        $res = [];
        for ($j = 0; $j < $countBatchCalls; $j++) {
            if ($j == 0) {
                $prevId = 0;
            } else {
                $prevId = max(ArrayHelper::getColumn($res, 'ID'));
            }
            for ($i = 0; $i < 50; $i++) {
                if ($countCalls > 0) {
                    $idx = $obB24->client->addBatchCall(
                        'crm.company.list',
                        [
                            'order' => ["ID" => "ASC"],
                            'filter' => [
                                '>ID' => $prevId
                            ],
                            'select' => ['ID'],
                            'start' => -1
                        ],
                        function ($result) use (&$res) {
                            $res = array_merge($res, $result['result']);
                        }
                    );
                    $prevId = '$result[' . $idx . '][49][ID]';
                }
                $countCalls--;
            }
            $obB24->client->processBatchCalls();
        }
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
            $obB24->client->addBatchCall(
                'crm.company.get',
                ['ID' => $id],
                function ($result) {
                    $data = ArrayHelper::getValue($result, 'result');
                    $id = ArrayHelper::getValue($data, 'ID');
                    $model = $this->modelClass::find()->where(['ID' => $id])->one();
                    if (!$model) {
                        $model = new $this->modelClass();
                    }
                    $model->loadData($data);
                }
            );
        }
        $obB24->client->processBatchCalls();
        return true;
    }
}
