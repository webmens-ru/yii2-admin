<?php

namespace wm\admin\controllers\settings\placements;

use wm\admin\models\settings\placements\Placement;
use wm\admin\models\settings\placements\PlacementDirectory;
use wm\admin\models\settings\placements\PlacementSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * PlacementController implements the CRUD actions for Placement model.
 */
class PlacementController extends \wm\admin\controllers\BaseModuleController
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
     * Lists all Placement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $placementDirectoryModel = PlacementDirectory::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'placementDirectoryModel' => $placementDirectoryModel,
        ]);
    }

    /**
     * Displays a single Placement model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Placement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Placement();
        $placementDirectoryModel = PlacementDirectory::find()->all();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'placementDirectoryModel' => $placementDirectoryModel,
        ]);
    }

    /**
     * Updates an existing Placement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $placementDirectoryModel = PlacementDirectory::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'placementDirectoryModel' => $placementDirectoryModel,
        ]);
    }

    /**
     * Deletes an existing Placement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionB24PlacementsList()
    {
    }

    public function actionB24Delete($id)
    {
        $model = $this->findModel($id);
        $model->removeBitrix24();
        return $this->render('b24-delete', [
            'model' => $model
        ]);
    }

    public function actionB24Install($id)
    {
        $model = $this->findModel($id);
        $model->toBitrix24();
        return $this->render('b24-install', [
            'model' => $model
        ]);
    }

    /**
     * Finds the Placement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Placement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Placement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
