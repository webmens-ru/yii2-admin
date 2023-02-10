<?php

namespace wm\admin\jobs\calendarEventUser;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class CalendarEventUserSynchronizationFullJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;
    public $userIds;
    public $from;


    public function execute($queue) //TODO до 50-ти сотрудников
    {
        $this->modelClass::deleteAll();
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        foreach ($this->userIds as $userId) {
            $listDataSelector = 'result';
            $params = [
                'type' => 'user',
                'ownerId' => $userId,
                'from' => $this->from,
            ];
            $request = $b24Obj->client->call(
                'calendar.event.get',
                $params
            );
            foreach (ArrayHelper::getValue($request, $listDataSelector) as $oneEntity) {
                $model = $this->modelClass::find()->where(['ID' => $oneEntity['ID']])->one();
                if (!$model) {
                    $model = Yii::createObject($this->modelClass);
                }
                $model->loadData($oneEntity);
            }
        }
    }
}
