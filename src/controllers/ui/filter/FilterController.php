<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterSearch;
use wm\admin\models\ui\filter\Filter;
use wm\admin\models\ui\filter\FilterField;
//use wm\admin\models\ui\filter\FilterFieldSetting;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * Class FilterController
 *
 * Данныый контроллер позволяет работать с сущностью Фильтр
 *
 * @package wm\admin\controllers\ui\filter
 */
class FilterController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = Filter::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterSearch::class;

    /**
     * Получение списка фильтров конкретной сущности
     *
     * @param $entity Название сущности
     * @return array
     *
     * ```php
     * [
     *    {
     *        "id": 2,
     *        "title": "project",
     *        "entityCode": "project",
     *        "isName": 0,
     *        "order": 1,
     *        "isBase": 0,
     *        "userId": 1,
     *        "parentId": 1
     *    }
     * ]
     * ```
     */
    public function actionItems($entity)
    {
        $userId = Yii::$app->user->id;
        $model = Filter::getItems($entity, $userId);
        return $model;
    }

    /**
     * @return mixed
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    /**
     * Создание фильтра
     *
     * Для создания фильтра в теле запроса необходимо передать следующие данные
     *
     * ```php
     * //title - Название фильтра
     * //order - Сортировка
     * //parentId - На основании какого фильтра создаётся данный фильтр.
     * //Все фильтры кроме базовых создаются на основании других фильтров
     * {
     *    "title":"Магазин",
     *    "order":3,
     *    "parentId":12
     * }
     * ```
     *
     */

    public function actionCreate()
    {
        $userId = Yii::$app->user->id;
        $params = Yii::$app->getRequest()->getBodyParams();
        $model = Filter::add($params, $userId);
        if ($model) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', $model->getPrimaryKey(true));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        }
        return $model;
    }

    /**
     * Получение параметров полей конкретного фильтра
     *
     * @param $filterId
     * @return array
     *
     * ```php
     * [
     *    {
     *        "id": 17,
     *        "filterId": 7,
     *        "filterFieldId": 4,
     *        "value": [
     *            "=>",
     *            "20",
     *            ""
     *        ],
     *        "title": "",
     *        "order": 0
     *    },
     *    {
     *        "id": 18,
     *        "filterId": 7,
     *        "filterFieldId": 5,
     *        "value": [
     *            "",
     *            "",
     *            ""
     *        ],
     *        "title": "",
     *        "order": 0
     *    }
     * ]
     * ```
     */
    public function actionFieldsSettings($filterId)
    {
        $userId = Yii::$app->user->id;
        $model = Filter::find()->where(['id' => $filterId, 'userId' => $userId])->one();
        $res = [];
        if ($model) {
            $res = $model->filterFieldSettings;
        }
        return $res;
    }

    /**
     * Получение списка полей фильтра
     *
     * @param $entity
     * @return mixed
     *
     * ```php
     * [
     *    {
     *        "id": 1,
     *        "entityCode": "project",
     *        "typeId": 5,
     *        "title": "Id",
     *        "order": 1,
     *        "type": {
     *            "id": 5,
     *            "name": "integer"
     *        },
     *        "filterFieldOptions": [],
     *        "code": "id"
     *    },
     *    {
     *        "id": 2,
     *        "entityCode": "project",
     *        "typeId": 1,
     *        "title": "Название",
     *        "order": 2,
     *        "type": {
     *            "id": 1,
     *            "name": "string"
     *        },
     *        "filterFieldOptions": [],
     *        "code": "title"
     *    }
     * ]
     * ```
     *
     */
    public function actionFields($entity)
    {
        $model = FilterField::find()->where(['entityCode' => $entity])->all();
        return $model;
    }

    /**
     * Изменение фильтра
     *
     * Для изменения фильтра в теле запроса необходимо передать следующие данные
     *
     * ```php
     * {
     *    "id":53,
     *    "title":"Название фильтра"
     * }
     * ```
     *
     * @param $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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

    /**
     * Удаление фильтра
     *
     * @param $id
     */
    public function actionDelete($id)
    {
        $userId = Yii::$app->user->id;
        $model = Filter::find()->where(['id' => $id, 'userId' => $userId])->one();

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
        Yii::$app->getResponse()->setStatusCode(204);
    }


    /**
     * Изменение сортировки фильтра
     *
     * Тело запроса
     *
     * ```php
     * [
     *    {
     *        "id":12,
     *        "order":2
     *    },
     *    {
     *        "id":13,
     *        "order":1
     *    }
     * ]
     * ```
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEditOrder()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        Filter::editOrder($params);
    }
}
