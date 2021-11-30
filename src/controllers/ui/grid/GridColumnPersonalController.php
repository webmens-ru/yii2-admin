<?php

namespace wm\admin\controllers\ui\grid;

use wm\admin\models\ui\grid\GridColumnPersonalSearch;
use wm\admin\models\ui\grid\GridColumnPersonal;
use Yii;

class GridColumnPersonalController extends \wm\admin\controllers\ActiveRestController {

    public $modelClass = GridColumnPersonal::class;
    public $modelClassSearch = GridColumnPersonalSearch::class;

    public function actionSaveSchema() {
        Yii::warning('actionSaveSchema', 'action');

        Yii::warning(Yii::$app->getRequest()->getBodyParams());
        $userId = Yii::$app->user->id;
        $columns = Yii::$app->getRequest()->getBodyParams();
        GridColumnPersonal::saveColumns($columns, $userId);

        return true;
    }

}
