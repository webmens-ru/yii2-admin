<?php

namespace wm\admin\controllers\ui\menu;

use wm\admin\models\ui\menu\MenuSearch;
use wm\admin\models\ui\menu\Menu;

class MenuController extends \wm\admin\controllers\ActiveRestController{
       
    public $modelClass = Menu::class;
    public $modelClassSearch = MenuSearch::class;
}
