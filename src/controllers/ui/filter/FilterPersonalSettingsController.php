<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterPersonalSettings;
use wm\admin\models\ui\filter\FilterPersonalSettingsSearch;
use Yii;

/**
 * Class FilterPersonalSettingsController
 * @package wm\admin\controllers\ui\filter
 */
class FilterPersonalSettingsController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = FilterPersonalSettings::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterPersonalSettingsSearch::class;

    /**
     * @return bool
     */
    public function actionSaveItems()
    {
        $userId = Yii::$app->user->id;
        $items = Yii::$app->getRequest()->getBodyParams();
        FilterPersonalSettings::saveItems($items, $userId);
        return true;
    }
}
