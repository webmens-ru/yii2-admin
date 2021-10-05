<?php

namespace wm\admin\controllers\handlers;

use Yii;
use app\modules\b24\controllers\B24Controller;
use app\modules\baseapp\models\settings\robots\Robots;
use app\modules\baseapp\models\B24ConnectSettings;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;

class RobotController extends B24Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actions() {
        $result = [];
        $actionFiles = FileHelper::findFiles('../controllers/handlers/robots');
        foreach ($actionFiles as $actionFile) {
            $result[Inflector::camel2id(basename($actionFile, "Action.php"))]['class'] = 'app\controllers\handlers\robots\\' . basename($actionFile, ".php");
        }
        return $result;
    }

}
