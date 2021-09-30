<?php

namespace wm\admin\controllers;

use Yii;
use \yii\web\HttpException;
use wm\admin\models\B24ConnectSettings;

class BaseController extends \yii\web\Controller {

    public $layout = '@vendor\webmens-ru\yii2-admin\views\layouts\admin.php';
    
    public function actionIndex() {
        return $this->render('index');
    }

}
