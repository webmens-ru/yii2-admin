<?php

namespace wm\admin\jobs\company;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class CompanySynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {

        $this->modelClass::deleteAll();
        $modelDeal = Yii::createObject($this->modelClass);
        $fieldsDeal = $modelDeal->attributes();
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
        $listDataSelector = 'result';
        $params = [
            'order' => ["ID" => "ASC"],
            'select' => $fieldsDeal,
            //'filter' => ['>ID'=> 133553],
        ];
        $request = $obB24->client->call('crm.company.list', $params);
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
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
                    $idx = $obB24->client->addBatchCall(
                        'crm.company.list',
                        [
                            'order' => ["ID" => "ASC"],
                            'filter' => [
                                '>ID' => $prevId
                            ],
                            'select' => $fieldsDeal,
                            'start' => -1
                        ],
                        function ($result) use ($listDataSelector, &$maxId) {
                            Yii::$app->db->close();
                            Yii::$app->db->open();
                            $data = ArrayHelper::getValue($result, $listDataSelector);
                            foreach ($data as $oneEntity) {
                                $model = Yii::createObject($this->modelClass);
                                $model->loadData($oneEntity);
                            }
                            $maxId = max(ArrayHelper::getColumn($data, 'ID'));
                        }
                    );
                    $prevId = '$result[' . $idx . '][49][ID]';
                }
                $countCalls--;
            }
            $obB24->client->processBatchCalls();
        }
    }
}
