<?php

namespace wm\admin\controllers;

use wm\admin\models\CrmStatus;

class CrmStatusController extends \wm\admin\controllers\ActiveRestController
{
    public $modelClass = CrmStatus::class;

    public function actionSynchronization()
    {
        if($this->modelClass::synchronization()['success'])
            return true;
        else
            return false;

    }
}