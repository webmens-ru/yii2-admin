<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterFieldSearch;
use wm\admin\models\ui\filter\FilterField;

class FilterFieldController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = FilterField::class; 
    public $modelClassSearch = FilterFieldSearch::class; 
}
