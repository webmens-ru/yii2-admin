<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldOptionsSearch;
use wm\admin\models\ui\filter\FilterFieldOptions;

/**
 * Class FilterFieldOptionsController
 * @package wm\admin\controllers\ui\filter
 */
class FilterFieldOptionsController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = FilterFieldOptions::class;
    /**
     * @var string
     */
    public $modelClassSearch = FilterFieldOptionsSearch::class;
}
