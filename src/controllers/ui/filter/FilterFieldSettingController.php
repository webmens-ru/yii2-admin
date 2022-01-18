<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldSettingSearch;
use wm\admin\models\ui\filter\FilterFieldSetting;
use Yii;

/**
 * Class FilterFieldSettingController
 * @package wm\admin\controllers\ui\filter
 */
class FilterFieldSettingController extends \wm\admin\controllers\ActiveRestController{

    /**
     * @var string
     */
    public $modelClass = FilterFieldSetting::class;


    /**
     * @var string
     */
    public $modelClassSearch = FilterFieldSettingSearch::class;

    /**
     * @return bool
     */
    public function actionEditOrder() {
        $params = Yii::$app->getRequest()->getBodyParams();
        FilterFieldSetting::editOrder($params);
        return true;
    }
}
