<?php

namespace wm\admin\controllers\settings\robots;

use Yii;
use wm\admin\models\settings\robots\RobotsProperties;
use wm\admin\models\settings\robots\RobotsPropertiesSearch;
use wm\admin\models\settings\robots\Robots;
use wm\admin\models\settings\robots\RobotsOptionsSearch;
use wm\admin\models\settings\robots\RobotsTypes;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * RobotsPropertiesController implements the CRUD actions for RobotsProperties model.
 */
class RobotsPropertiesController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RobotsProperties models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RobotsPropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $robotsModel = Robots::find()->all();
        $robotsTypesModel = RobotsTypes::find()->all();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'robotsModel' => $robotsModel,
                    'robotsTypesModel' => $robotsTypesModel,
        ]);
    }

    /**
     * Displays a single RobotsProperties model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($robot_code, $system_name) {
        $model = $this->findModel($robot_code, $system_name);
        $searchModel = new RobotsOptionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->robot_code, $model->system_name);
        
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new RobotsProperties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($robotCode) {
        $model = new RobotsProperties();
        $robotsTypesModel = RobotsTypes::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['settings/robots/robots/view', 'code' => $model->robot_code]);
        }
        $request = Yii::$app->request;
        if ($request->post('action') != 'submit') {
            $model->sort = 500;
            $model->is_in = 1;
            $model->robot_code = $robotCode;
        }
        return $this->render('create', [
                    'model' => $model,
                    'robotsTypesModel' => $robotsTypesModel,
        ]);
    }

    /**
     * Updates an existing RobotsProperties model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($robot_code, $system_name) {
        $model = $this->findModel($robot_code, $system_name);
        $robotsModel = Robots::find()->all();
        $robotsTypesModel = RobotsTypes::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['settings/robots/robots/view', 'code' => $model->robot_code]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'robotsModel' => $robotsModel,
                    'robotsTypesModel' => $robotsTypesModel,
        ]);
    }

    /**
     * Deletes an existing RobotsProperties model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($robot_code, $system_name) {
        $this->findModel($robot_code, $system_name)->delete();
       
        return $this->redirect(['settings/robots/robots/view', 'code' => $robot_code]);
    }

    /**
     * Finds the RobotsProperties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RobotsProperties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($robot_code, $system_name) {
        if (($model = RobotsProperties::findOne(['robot_code' => $robot_code, 'system_name' => $system_name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
