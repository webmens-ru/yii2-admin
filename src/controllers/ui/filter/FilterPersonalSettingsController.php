<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterPersonalSettings;
use wm\admin\models\ui\filter\FilterPersonalSettingsSearch;
use Yii;

class FilterPersonalSettingsController extends \wm\admin\controllers\ActiveRestController {

    public $modelClass = FilterPersonalSettings::class;
    public $modelClassSearch = FilterPersonalSettingsSearch::class;

    public function actionSaveItems() {

        Yii::warning(Yii::$app->getRequest()->getBodyParams());
        $userId = Yii::$app->user->id;
        $items = Yii::$app->getRequest()->getBodyParams();
        FilterPersonalSettings::saveItems($items, $userId);

        return true;
    }

}
