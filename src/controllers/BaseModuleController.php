<?php

namespace wm\admin\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BaseModuleController extends \yii\web\Controller
{
    public $layout = 'admin.php';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['about'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['canAdmin'],
                    ],
                ],
            ],
        ];
    }
//
}
