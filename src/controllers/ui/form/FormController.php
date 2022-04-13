<?php

namespace wm\admin\controllers\ui\form;

use wm\admin\models\ui\form\Fields;
use wm\admin\models\ui\form\Forms;

class FormController extends \wm\admin\controllers\ActiveRestController
{
    public $modelClass = Forms::class;
}