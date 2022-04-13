<?php

namespace wm\admin\controllers\ui\form;

use wm\admin\models\ui\form\Forms;

/**
 * Class FormController
 * @package wm\admin\controllers\ui\form
 */
class FormController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = Forms::class;
}