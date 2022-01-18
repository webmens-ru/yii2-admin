<?php

namespace wm\admin\controllers\ui\menu;

use wm\admin\models\ui\menu\MenuItemPersonalSettings;
use wm\admin\models\ui\menu\MenuItemPersonalSettingsSearch;
use Yii;


/**
 * Class MenuItemPersonalSettingsController
 * @package wm\admin\controllers\ui\menu
 */
class MenuItemPersonalSettingsController extends \wm\admin\controllers\ActiveRestController{

    /**
     * @var string
     */
    public $modelClass = MenuItemPersonalSettings::class;
    /**
     * @var string
     */
    public $modelClassSearch = MenuItemPersonalSettingsSearch::class;

    /**
     * @return bool
     */
    public function actionSaveItems() {
       
		Yii::warning(Yii::$app->getRequest()->getBodyParams());
        $userId = Yii::$app->user->id;
		$items = Yii::$app->getRequest()->getBodyParams();
        MenuItemPersonalSettings::saveItems($items, $userId);

       return true;
    }
}
