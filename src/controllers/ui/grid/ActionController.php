<?php

namespace wm\admin\controllers\ui\grid;

use wm\admin\models\ui\grid\ActionSearch;
use wm\admin\models\ui\grid\Action;
use Yii;

/**
 * @deprecated
 * Class GridColumnController
 * @package wm\admin\controllers\ui\grid
 */
class ActionController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = Action::class;
    /**
     * @var string
     */
    public $modelClassSearch = ActionSearch::class;

    /**
     * @param string $entity
     * @return mixed
     */
    public function actionEntityActions($entity)
    {
        return $this->modelClass::find()->where(['entityCode' => $entity])->all();
    }
}
