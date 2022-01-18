<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterItemPersonalSettings;
use wm\admin\models\ui\filter\FilterItemPersonalSettingsSearch;
use Yii;

/**
 * Class FilterItemPersonalSettingsController
 * @package wm\admin\controllers\ui\filter
 */
class FilterItemPersonalSettingsController extends \wm\admin\controllers\ActiveRestController {

    /**
     * @var string
     */
    public $modelClass = FilterItemPersonalSettings::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterItemPersonalSettingsSearch::class;

    /**
     * @return bool
     */
    public function actionSaveItems() {
        $userId = Yii::$app->user->id;
        $items = Yii::$app->getRequest()->getBodyParams();
        FilterItemPersonalSettings::saveItems($items, $userId);

        return true;
    }
}
