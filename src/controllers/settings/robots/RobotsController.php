<?php

namespace wm\admin\controllers\settings\robots;

use Yii;
use wm\admin\models\settings\robots\Robots;
use wm\admin\models\settings\robots\RobotsSearch;
use wm\admin\models\settings\robots\RobotsImport;
use wm\admin\models\settings\robots\RobotsPropertiesSearch;
use wm\admin\models\settings\robots\RobotsTypes;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * RobotsController implements the CRUD actions for Robots model.
 */
class RobotsController extends \wm\admin\controllers\BaseModuleController
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

    /**
     * Lists all Robots models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RobotsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Robots model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($code)
    {
        $model = $this->findModel($code);
        $searchModel = new RobotsPropertiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->code);
        $robotsTypesModel = RobotsTypes::find()->all();

        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'robotsTypesModel' => $robotsTypesModel,
        ]);
    }

    /**
     * Creates a new Robots model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Robots();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'code' => $model->code]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Robots model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $model = $this->findModel($code);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'code' => $model->code]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Robots model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($code)
    {
        $this->findModel($code)->delete();

        return $this->redirect(['settings/robots/robots/index']);
    }

    public function actionB24Delete($code)
    {
        $model = $this->findModel($code);
        $model->removeBitrix24();
        return $this->render('delete', [
                    'model' => $model
        ]);
    }

    public function actionInstall($code)
    {
        $model = $this->findModel($code);
        $model->toBitrix24();
        return $this->render('install', [
                    'model' => $model
        ]);
    }

    public function actionExport($code)
    {
        $model = $this->findModel($code);
        $fileName = $model->export();
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=archive.zip");
        header("Content-length: " . filesize($fileName));
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$fileName");
        unlink($fileName);
    }

    public function actionFileImport()
    {
        $model = new RobotsImport();
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
        }
        if ($model->validate()) {
            if ($model->file) {
                $model->import();
            }
            return $this->redirect(['index']);
        }
        return $this->render('file-import', [
                    'model' => $model,
        ]);
    }

    /**
     * Finds the Robots model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Robots the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = Robots::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
