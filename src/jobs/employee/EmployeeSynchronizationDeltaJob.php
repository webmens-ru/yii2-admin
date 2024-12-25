<?php

namespace wm\admin\jobs\employee;

use Bitrix24\B24Object;
use wm\admin\models\settings\events\Events;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 *
 */
class EmployeeSynchronizationDeltaJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @param $queue
     * @return void
     */
    public function execute($queue)
    {
        $this->modelClass::deleteAll();
        $arrayId = $this->getIdsBatch();
        if ($arrayId) {
            $this->getB24Get($arrayId);
        }
    }

    /**
     * @return mixed[]
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
     * @throws \yii\db\Exception
     */
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

    /**
     * @param int[] $arrayId
     * @return true
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function getB24Get($arrayId)
    {
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        foreach ($arrayId as $id) {
//            try{}catch ()//TODO
            $obB24->client->addBatchCall(
                'user.get',
                ['ID' => $id],
                function ($result) {
                    $data = ArrayHelper::getValue($result, 'result')[0];
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
