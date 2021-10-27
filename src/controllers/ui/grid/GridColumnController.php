<?php

namespace wm\admin\controllers\ui\grid;

use wm\admin\models\ui\grid\GridColumnSearch;
use wm\admin\models\ui\grid\GridColumn;
use Yii;

class GridColumnController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = GridColumn::class;
    public $modelClassSearch = GridColumnSearch::class;
    
    public function actionSchema($entity){
        $userId = Yii::$app->user->id;
        $model = GridColumn::getColumns($entity, $userId);
        return $model;
    }
    
    
    
    
}
