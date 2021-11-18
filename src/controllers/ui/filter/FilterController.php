<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterSearch;
use wm\admin\models\ui\filter\Filter;
use wm\admin\models\ui\filter\FilterField;
use wm\admin\models\ui\filter\FilterFieldSetting;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class FilterController extends \wm\admin\controllers\ActiveRestController {

    public $modelClass = Filter::class;
    public $modelClassSearch = FilterSearch::class;

//для первоначального построение списка элементов фильтра
    public function actionItems($entity) {
        $userId = Yii::$app->user->id;
        $model = Filter::getItems($entity, $userId);
        return $model;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    public function actionCreate() {

        //Yii::warning(Yii::$app->getRequest()->getBodyParams());
        $userId = Yii::$app->user->id;
        $params = Yii::$app->getRequest()->getBodyParams();
        $model = Filter::add($params, $userId);
        if ($model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', $model->getPrimaryKey(true));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function actionFieldsSettings($filterId) {
        $userId = Yii::$app->user->id;
        $model = Filter::find()->where(['id' => $filterId, 'userId' => $userId])->one();
        $res = [];
        if ($model) {
            $res = $model->filterFieldSettings;
        }
        return $res;
    }

    public function actionFields($entity) {
        $model = FilterField::find()->where(['entityCode' => $entity])->all();
        return $model;
    }

    public function actionUpdate($id) {
        $userId = Yii::$app->user->id;

        if (($model = Filter::find()->where(['id' => $id, 'userId' => $userId])->one()) === null) {
            throw new ServerErrorHttpException('The requested page does not exist.');
        }

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }

    public function actionDelete($id) {
        $userId = Yii::$app->user->id;
        $model = Filter::find()->where(['id' => $id, 'userId' => $userId])->one();

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    public function actionEditOrder() {
        $params = Yii::$app->getRequest()->getBodyParams();
        Filter::editOrder($params);
    }

}
