<?php

namespace wm\admin\controllers\ui\grid;

use wm\admin\models\ui\grid\GridColumnSearch;
use wm\admin\models\ui\grid\GridColumn;
use Yii;

/**
 * Class GridColumnController
 * @package wm\admin\controllers\ui\grid
 */
class GridColumnController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = GridColumn::class;
    /**
     * @var string
     */
    public $modelClassSearch = GridColumnSearch::class;

    /**
     * @param string|null $entity
     * @return array|mixed
     */
    public function actionSchema($entity = null)
    {
        $userId = intval(Yii::$app->user->id);
        $model = GridColumn::getColumns($entity?:'', $userId);
        return $model;
    }
}
