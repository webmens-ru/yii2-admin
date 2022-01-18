<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterItemSearch;
use wm\admin\models\ui\filter\FilterItem;
use Yii;

/**
 * Class FilterItemController
 * @package wm\admin\controllers\ui\filter
 */
class FilterItemController extends \wm\admin\controllers\ActiveRestController {

    /**
     * @var string
     */
    public $modelClass = FilterItem::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterItemSearch::class;

    /**
     * @param $menuId
     * @return array
     */
    public function actionItems($menuId) {
        $userId = Yii::$app->user->id;
        $model = FilterItem::getItems($menuId, $userId);
        return $model;
    }

    /**
     * @return mixed
     */
    public function actionSaveItems() {
		$name = Yii::$app->getRequest()->getBodyParams(); 

       return $name;
    }
}