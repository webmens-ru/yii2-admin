<?php

namespace wm\admin\jobs\crmstatus;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class CrmStatusSynchronizationFullGetJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {
        $this->modelClass::deleteAll();
        $modelCrmStatus = Yii::createObject($this->modelClass);
        $fieldsCrmStatus = $modelCrmStatus->attributes();
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $listDataSelector = 'result';
        $params = [];
        $request = $b24Obj->client->call(
            'crm.status.list',
            $params
        );
        foreach (ArrayHelper::getValue($request, $listDataSelector) as $oneEntity) {
            $model = Yii::createObject($this->modelClass);
            $model->loadData($oneEntity);
        }
    }
}
