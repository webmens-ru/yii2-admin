<?php

namespace wm\admin\controllers\settings\robots;

use Yii;
use wm\admin\models\settings\robots\RobotsOptions;
use wm\admin\models\settings\robots\RobotsOptionsSearch;
use wm\admin\models\settings\robots\RobotsProperties;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * RobotsOptionsController implements the CRUD actions for RobotsOptions model.
 */
class RobotsOptionsController extends Controller {

    public $layout = 'admin.php';
    
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
     * Lists all RobotsOptions models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RobotsOptionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $robotsPropertiesModel = RobotsProperties::find()->all();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'robotsPropertiesModel' => $robotsPropertiesModel,
        ]);
    }

    /**
     * Displays a single RobotsOptions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($property_name, $robot_code, $value) {
        return $this->render('view', [
                    'model' => $this->findModel($property_name, $robot_code, $value),
        ]);
    }

    /**
     * Creates a new RobotsOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($robotCode, $propertyName) {
        $model = new RobotsOptions();
        $robotsPropertiesModel = RobotsProperties::find()->where(['type_id' => '6'])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['settings/robots/robots-properties/view', 'system_name' => $model->property_name, 'robot_code' => $model->robot_code]);
        }
        $request = Yii::$app->request;
        if ($request->post('action') != 'submit') {
            $model->sort = 500;
            $model->property_name = $propertyName;
            $model->robot_code = $robotCode;
        }

        return $this->render('create', [
                    'model' => $model,
                    'robotsPropertiesModel' => $robotsPropertiesModel,
        ]);
    }

    /**
     * Updates an existing RobotsOptions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($property_name, $robot_code, $value) {
        $model = $this->findModel($property_name, $robot_code, $value);
        $robotsPropertiesModel = RobotsProperties::getPropertiesTypeSelectStatic();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['settings/robots/robots-properties/view', 'system_name' => $model->property_name, 'robot_code' => $model->robot_code]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'robotsPropertiesModel' => $robotsPropertiesModel,
        ]);
    }

    /**
     * Deletes an existing RobotsOptions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($property_name, $robot_code, $value) {
        $this->findModel($property_name, $robot_code, $value)->delete();

        return $this->redirect(['settings/robots/robots-properties/view', 'system_name' => $property_name, 'robot_code' => $robot_code]);
    }

    /**
     * Finds the RobotsOptions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RobotsOptions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($property_name, $robot_code, $value) {
        if (($model = RobotsOptions::findOne($property_name, $robot_code, $value)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
