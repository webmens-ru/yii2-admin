<?php

namespace wm\admin\jobs\taskElapsedItem;

use Bitrix24\B24Object;
use Bitrix24\Task\Task;
use wm\admin\models\settings\events\Events;
use wm\b24tools\b24Tools;
use wm\yii\db\ActiveRecord;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 *
 */
class TaskElapsedItemSynchronizationDeltaJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var int[]
     */
    public $userIds;


    /**
     * @param $queue
     * @return void
     */
    public function execute($queue)
    {
        Yii::warning('TaskElapsedItemSynchronizationDeltaJob', 'TaskElapsedItemSynchronizationDeltaJob');
    }


    /**
     * @return void
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function synchronAdd()
    {
        $lastModel = $this->modelClass::find()->orderBy(['ID' => SORT_DESC])->limit(1)->one();


        $modelTask = Yii::createObject($this->modelClass);
        $fieldsTask = $modelTask->attributes();
        $component = new b24Tools();
        Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result';
        $params = [
            "ORDER" => array("ID" => "ASC"),
            "FILTER" => ['>ID' => $lastModel->ID],
            "SELECT" => ['*'],
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
            for ($i = 2; $i <= $countCalls; $i++) {
                $params['PARAMS']['NAV_PARAMS'] = [
                    'nPageSize' => 50,
                    'iNumPage' => $i
                ];
                $b24Obj->client->addBatchCall(
                    'task.elapseditem.getlist',
                    $params,
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
