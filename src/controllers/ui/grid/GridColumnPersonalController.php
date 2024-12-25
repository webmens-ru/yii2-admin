<?php

namespace wm\admin\controllers\ui\grid;

use wm\admin\models\ui\grid\GridColumnPersonalSearch;
use wm\admin\models\ui\grid\GridColumnPersonal;
use wm\yii\helpers\ArrayHelper;
use Yii;

/**
 * Class GridColumnPersonalController
 * @package wm\admin\controllers\ui\grid
 */
class GridColumnPersonalController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = GridColumnPersonal::class;
    /**
     * @var string
     */
    public $modelClassSearch = GridColumnPersonalSearch::class;

    /**
     * @return bool
     */
    public function actionSaveSchema()
    {
        $userId = intval(Yii::$app->user->id);
        $columns = Yii::$app->getRequest()->getBodyParams();  //TODO переделать
        GridColumnPersonal::saveColumns($columns, $userId); //@phpstan-ignore-line

        return true;
    }

    /**
     * @return GridColumnPersonal
     * @throws \yii\base\InvalidConfigException
     */
    public function actionFrozen()
    {
        $data = Yii::$app->getRequest()->getBodyParams();
        $entity = ArrayHelper::getValue($data, 'entity');
        $columnTitle =  ArrayHelper::getValue($data, 'title');
        $frozen = ArrayHelper::getValue($data, 'frozen');
        $userId = intval(Yii::$app->user->id);
        return GridColumnPersonal::setFrozen($entity, $columnTitle, $frozen, $userId);
    }
}
