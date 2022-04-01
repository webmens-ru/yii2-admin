<?php

namespace wm\admin\controllers;

use wm\admin\models\CrmUser;

class CrmUserController extends \wm\admin\controllers\ActiveRestController
{
    public $modelClass = CrmUser::class;

    public function actionSynchronization()
    {
        if($this->modelClass::synchronization()['success'])
            return true;
        else
            return false;

    }
}