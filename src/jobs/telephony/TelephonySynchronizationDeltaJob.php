<?php

namespace wm\admin\jobs\telephony;

use Bitrix24\Bitrix24Entity;
use wm\admin\models\settings\events\Events;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class TelephonySynchronizationDeltaJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    public function execute($queue)
    {
        $isSyncAdd = false;
        while (!$isSyncAdd) {
            $isSyncAdd = $this->synchronAdd();
        }
    }

    public function synchronAdd()
    {
        $answerB24 = Events::getOffline('OnVoximplantCallEnd');
        $eventsB24 = ArrayHelper::getValue($answerB24, 'result.events');
        $arrayId = ArrayHelper::getColumn($eventsB24, 'EVENT_DATA.CALL_ID');
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

    public function getB24List($arrayId)
    {
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $answerB24 = $obB24->client->call(
            'voximplant.statistic.get',
            [
                'FILTER' => ["CALL_ID" => $arrayId],
            ],
        )['result'];
        return $answerB24;
    }
}
