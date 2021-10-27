<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldOptionsSearch;
use wm\admin\models\ui\filter\FilterFieldOptions;

class FilterFieldOptionsController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = FilterFieldOptions::class; 
    public $modelClassSearch = FilterFieldOptionsSearch::class; 
}
