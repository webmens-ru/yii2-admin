<?php

namespace wm\admin\controllers\ui;

use wm\admin\models\ui\EntitySearch;
use wm\admin\models\ui\Entity;

/**
 *
 */
class EntityController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = Entity::class;
    /**
     * @var string
     */
    public $modelClassSearch = EntitySearch::class;
}
