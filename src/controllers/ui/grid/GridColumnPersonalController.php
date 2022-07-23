<?php

namespace wm\admin\controllers\ui\grid;

use wm\admin\models\ui\grid\GridColumnPersonalSearch;
use wm\admin\models\ui\grid\GridColumnPersonal;
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
        $userId = Yii::$app->user->id;
        $columns = Yii::$app->getRequest()->getBodyParams();
        GridColumnPersonal::saveColumns($columns, $userId);

        return true;
    }
}
