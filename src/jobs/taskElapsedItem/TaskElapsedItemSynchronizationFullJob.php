<?php

namespace wm\admin\jobs\taskElapsedItem;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 *
 */
class TaskElapsedItemSynchronizationFullJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var string
     */
    public $modelClass;


    /**
     * @param $queue
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
    public function execute($queue) //TODO до 50-ти сотрудников
    {
        $this->modelClass::deleteAll();
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
