<?php

namespace wm\admin\controllers\gii;

use wm\admin\models\gii\smartprocess\TableGenerator;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * RobotsController implements the CRUD actions for Robots model.
 */
class SmartProcessController extends \wm\admin\controllers\BaseModuleController
{

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


    public function actionTableGenerator()
    {
        $model = new TableGenerator();
        $model->load(Yii::$app->request->post());
        if(!$model->tableFields){
            $model->tableFields = false;
        }
        $error = null;

        if ($model->validate()) {
            if($model->createTable()){
                return $this->render('table-generator-confirm', [
                    'model' => $model,
                ]);
            }else{
                $error = 'Произошла ошибка при создании таблицы! Возможно таблица с таким именем уже существует';
            }

        }
        
        $fieldsList = $model->getFieldsList();

        $smartProcessList = ArrayHelper::map(TableGenerator::getSmartProcess(), 'entityTypeId', 'title');

        return $this->render('table-generator', [
            'fieldsList' => $fieldsList,
            'error' => $error,
            'smartProcessList' => $smartProcessList,
            'model' => $model,
        ]);
    }

    public function actionGetFields()
    {
        
        $model = new TableGenerator();
        $error = null;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->createTable()){
                return $this->render('table-generator-confirm', [
                    'model' => $model,
                ]);
            }else{
                $error = 'Произошла ошибка при создании таблицы! Возможно таблица с таким именем уже существует';
            }

        }

        $smartProcessList = ArrayHelper::map(TableGenerator::getSmartProcess(), 'entityTypeId', 'title');

        return $this->render('table-generator', [
            'error' => $error,
            'smartProcessList' => $smartProcessList,
            'model' => $model,
        ]);
    }
}
