<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldSettingSearch;
use wm\admin\models\ui\filter\FilterFieldSetting;
use Yii;

class FilterFieldSettingController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = FilterFieldSetting::class;
    
    
    public $modelClassSearch = FilterFieldSettingSearch::class;
    
     public function actionEditOrder() {
        $params = Yii::$app->getRequest()->getBodyParams();
        FilterFieldSetting::editOrder($params);
        return true;
    }
//    
//     public function actions() {
//        $actions = parent::actions();
//        unset($actions['create']);
//        //unset($actions['update']);
//        //unset($actions['delete']);
//        return $actions;
//    }
//    
//    public function actionCreate() {
//         $params = Yii::$app->getRequest()->getBodyParams();
//         $model = new FilterFieldSetting();
//         if($model->load($params) && $model->save()){
//             return true;
//         }
//         
//        Yii::error($model->errors, 'FilterFieldSettingController actionCreate');
//        return false;
//    }
    
    
}
