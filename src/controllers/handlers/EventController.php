<?php

namespace app\modules\baseapp\controllers\handlers;

use wm\b24tools\b24Tools;
use Yii;
use app\modules\baseapp\models\settings\events\Events;

/**
 * MaterialsGroupController implements the CRUD actions for MaterialsGroup model.
 */
class EventController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionE()
    {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $properties = $request->post('properties');
        $component = new b24Tools();
        $b24App = $component->connectFromUser($auth);
        //$obB24 = new \Bitrix24\Sale\Order($b24App);
        //$b24 = $obB24->update($properties['orderId'], ['statusId' => $properties['orderStatus']]);
        return '';
    }

    public function actionA()
    {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $properties = $request->post('properties');
        $component = new b24Tools();
        $b24App = $component->connectFromUser($auth);
        //$obB24 = new \Bitrix24\Sale\Order($b24App);
        //$b24 = $obB24->update($properties['orderId'], ['statusId' => $properties['orderStatus']]);
        return '';
    }
}
