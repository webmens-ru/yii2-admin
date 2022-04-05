<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldSearch;
use wm\admin\models\ui\filter\FilterField;

/**
 * Class FilterFieldController
 * @package wm\admin\controllers\ui\filter
 */
class FilterFieldController extends \wm\admin\controllers\ActiveRestController{

    /**
     * @var string
     */
    public $modelClass = FilterField::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterFieldSearch::class; 
}
