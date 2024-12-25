<?php

namespace wm\admin\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 *
 */
class BaseModuleController extends \yii\web\Controller
{
    /**
     * @var string
     */
    public $layout = 'admin.php';

    /**
     * @return mixed[]
     */
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
