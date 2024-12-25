<?php

namespace wm\admin\controllers\handlers;

use Yii;
use app\modules\baseapp\models\settings\robots\Robots;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;

/**
 *
 */
class RobotController extends \yii\web\Controller
{
    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @return mixed[]
     */
    public function actions()
    {
        $result = [];
        $actionFiles = FileHelper::findFiles('../controllers/handlers/robots');
        foreach ($actionFiles as $actionFile) {
            $result[
                Inflector::camel2id(
                    basename(
                        $actionFile,
                        "Action.php"
                    )
                )
            ]['class'] = 'app\controllers\handlers\robots\\' . basename($actionFile, ".php");
        }
        return $result;
    }
}
