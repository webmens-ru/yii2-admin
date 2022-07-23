<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldTypeSearch;
use wm\admin\models\ui\filter\FilterFieldType;

/**
 * Class FilterFieldTypeController
 * @package wm\admin\controllers\ui\filter
 */
class FilterFieldTypeController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = FilterFieldType::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterFieldTypeSearch::class;
}
