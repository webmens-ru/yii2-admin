<?php

namespace app\modules\baseapp\controllers\handlers;

use wm\b24tools\b24Tools;
use Yii;
use app\modules\baseapp\models\settings\placements\Placement;

/**
 * MaterialsGroupController implements the CRUD actions for MaterialsGroup model.
 */
class PlacementController extends \yii\web\Controller
{
    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     */
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

    /**
     * @return string
     * @throws \yii\db\Exception
     */
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
