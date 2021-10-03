<?php

namespace wm\admin\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BaseController extends \wm\admin\controllers\BaseModuleController {
    
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
                        'actions' => [
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['canAdmin'],
                    ],                    
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        return $this->render('index');
    }

}
