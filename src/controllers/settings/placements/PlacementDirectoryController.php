<?php

namespace wm\admin\controllers\settings\placements;

use Yii;
use wm\admin\models\settings\placements\PlacementDirectory;
use wm\admin\models\settings\placements\PlacementDirectorySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * PlacementDirectoryController implements the CRUD actions for PlacementDirectory model.
 */
class PlacementDirectoryController extends \wm\admin\controllers\BaseModuleController
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
     * Lists all PlacementDirectory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacementDirectorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $placementsCategories = PlacementDirectory::$CATEGORIES;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'placementsCategories' => $placementsCategories,
        ]);
    }

    /**
     * Displays a single PlacementDirectory model.
     * @param string $id
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
     * Creates a new PlacementDirectory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlacementDirectory();
        $placementsCategories = PlacementDirectory::$CATEGORIES;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'placementsCategories' => $placementsCategories,
        ]);
    }

    /**
     * Updates an existing PlacementDirectory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $placementsCategories = PlacementDirectory::$CATEGORIES;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'placementsCategories' => $placementsCategories,
        ]);
    }

    /**
     * Deletes an existing PlacementDirectory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PlacementDirectory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PlacementDirectory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlacementDirectory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
