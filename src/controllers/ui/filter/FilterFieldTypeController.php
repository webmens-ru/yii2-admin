<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldTypeSearch;
use wm\admin\models\ui\filter\FilterFieldType;

class FilterFieldTypeController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = FilterFieldType::class;
    public $modelClassSearch = FilterFieldTypeSearch::class;
}
