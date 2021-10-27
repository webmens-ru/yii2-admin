<?php

namespace wm\admin\controllers\ui\menu;

use wm\admin\models\ui\menu\MenuItemPersonalSettings;
use wm\admin\models\ui\menu\MenuItemPersonalSettingsSearch;
use Yii;


class MenuItemPersonalSettingsController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = MenuItemPersonalSettings::class; 
    public $modelClassSearch = MenuItemPersonalSettingsSearch::class; 
    
    public function actionSaveItems() {
       
		Yii::warning(Yii::$app->getRequest()->getBodyParams());
        $userId = Yii::$app->user->id;
		$items = Yii::$app->getRequest()->getBodyParams();
        MenuItemPersonalSettings::saveItems($items, $userId);

       return true;
    }
}
