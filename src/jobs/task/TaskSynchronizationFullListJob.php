<?php

namespace wm\admin\jobs\task;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 *
 */
class TaskSynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
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
    public function execute($queue)
    {
        $this->modelClass::deleteAll();
        $modelTask = Yii::createObject($this->modelClass);

        $fields = $modelTask->attributes();
        $fieldsTask = [];
        foreach ($fields as $field) {
            $str = Inflector::camel2words($field);
            $str = preg_replace("/(\d+)/", " $1", $str);
            $str = str_replace(" ", "_", strtolower(strval($str)));
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

        foreach (ArrayHelper::getValue($request, $listDataSelector) as $oneEntity) {
            $model = Yii::createObject($this->modelClass);
            $model->loadData($oneEntity);
        }
        $countCalls = (int)ceil($request['total'] / $b24Obj->client::MAX_BATCH_CALLS);
        $data = ArrayHelper::getValue($request, $listDataSelector);
        if (count($data) != $request['total']) {
            for ($i = 1; $i < $countCalls; $i++) {
                $b24Obj->client->addBatchCall(
                    'tasks.task.list',
                    array_merge($params, ['start' => $b24Obj->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use ($listDataSelector) {
                        Yii::$app->db->close();
                        Yii::$app->db->open();
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
