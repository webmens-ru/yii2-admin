<?php

namespace wm\admin\jobs\requisite;

use wm\admin\models\settings\events\Events;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class RequisiteSynchronizationDeltaJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {
        $isSyncAdd = false;
        while (!$isSyncAdd) {
            $isSyncAdd = $this->synchronAdd();
        }

        $isSyncUpdate = false;
        while (!$isSyncUpdate) {
            $isSyncUpdate = self::synchronUpdate();
        }

        $isSyncDelete = false;
        while (!$isSyncDelete) {
            $isSyncDelete = self::synchronDelete();
        }
    }

    public function synchronAdd()
    {
        Yii::$app->params['logPath'] = 'log/';
        $answerB24 = Events::getOffline('onCrmRequisiteAdd');
        $eventsB24 = ArrayHelper::getValue($answerB24, 'result.events');
        $arrayId = ArrayHelper::getColumn($eventsB24, 'EVENT_DATA.FIELDS.ID');
        if ($arrayId) {
            $B24List = $this->getB24List($arrayId);
            foreach ($B24List as $oneEntity) {
                $model = $this->modelClass::find()->where(['ID' => $oneEntity['ID']])->one();
                if (!$model) {
                    $model = new $this->modelClass();
                }
                $model->loadData($oneEntity);
            }
        }
        return count($arrayId) < 50 ? true : false;
    }

    public function synchronUpdate()
    {
        Yii::$app->params['logPath'] = 'log/';
        $answerB24 = Events::getOffline('onCrmRequisiteUpdate');
        $eventsB24 = ArrayHelper::getValue($answerB24, 'result.events');
        $arrayId = ArrayHelper::getColumn($eventsB24, 'EVENT_DATA.FIELDS.ID');
        if ($arrayId) {
            $B24List = $this->getB24List($arrayId);
            foreach ($B24List as $oneEntity) {
                $model = $this->modelClass::find()->where(['ID' => $oneEntity['ID']])->one();
                if (!$model) {
                    $model = new $this->modelClass();
                }
                $model->loadData($oneEntity);
            }
        }
        return count($arrayId) < 50 ? true : false;
    }

    public function synchronDelete()
    {
        Yii::$app->params['logPath'] = 'log/';
        $answerB24 = Events::getOffline('onCrmRequisiteDelete');
        $eventsB24 = ArrayHelper::getValue($answerB24, 'result.events');
        $arrayId = ArrayHelper::getColumn($eventsB24, 'EVENT_DATA.FIELDS.ID');
        if ($arrayId) {
            foreach ($arrayId as $id) {
                $model = $this->modelClass::find()->where(['id' => $id])->one();
                if ($model) {
                    $model->delete();
                }
            }
        }
        return count($arrayId) < 50 ? true : false;
    }

    public function getB24List($arrayId)
    {
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $res = [];
        foreach ($arrayId as $id) {
            $obB24->client->addBatchCall(
                'crm.requisite.get',
                ['ID' => $id],
                function ($result) use (&$res) {
                    $res[] = ArrayHelper::getValue($result, 'result');
                }
            );
        }
        $obB24->client->processBatchCalls();
        return $res;
    }
}
