<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterItemPersonalSettings;
use wm\admin\models\ui\filter\FilterItemPersonalSettingsSearch;
use Yii;


class FilterItemPersonalSettingsController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = FilterItemPersonalSettings::class; 
    public $modelClassSearch = FilterItemPersonalSettingsSearch::class;
    
    public function actionSaveItems() {
       
		Yii::warning(Yii::$app->getRequest()->getBodyParams());
        $userId = Yii::$app->user->id;
		$items = Yii::$app->getRequest()->getBodyParams();
        FilterItemPersonalSettings::saveItems($items, $userId);

       return true;
    }
}
