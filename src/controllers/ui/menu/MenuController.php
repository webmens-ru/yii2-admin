<?php

namespace wm\admin\controllers\ui\menu;

use wm\admin\models\ui\menu\MenuSearch;
use wm\admin\models\ui\menu\Menu;

/**
 * Class MenuController
 * @package wm\admin\controllers\ui\menu
 */
class MenuController extends \wm\admin\controllers\ActiveRestController{

    /**
     * @var string
     */
    public $modelClass = Menu::class;
    /**
     * @var string
     */
    public $modelClassSearch = MenuSearch::class;
}
