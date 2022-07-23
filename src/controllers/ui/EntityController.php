<?php

namespace wm\admin\controllers\ui;

use wm\admin\models\ui\EntitySearch;
use wm\admin\models\ui\Entity;

class EntityController extends \wm\admin\controllers\ActiveRestController
{
    public $modelClass = Entity::class;
    public $modelClassSearch = EntitySearch::class;
}
