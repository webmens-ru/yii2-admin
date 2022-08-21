<?php

namespace wm\admin\controllers;

use wm\admin\models\Synchronization;
use wm\admin\models\synchronization\FormFullSync;
use wm\admin\models\synchronization\FormSync;
use wm\admin\models\synchronization\SynchronizationFieldSearch;
use wm\admin\models\SynchronizationSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * SynchronizationController implements the CRUD actions for Synchronization model.
 */
class SynchronizationController extends \wm\admin\controllers\BaseModuleController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Synchronization models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SynchronizationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Synchronization model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->getCount();
        $searchModel = new SynchronizationFieldSearch();
        $dataProvider = $searchModel->search(
            array_merge(
                Yii::$app->request->queryParams,
                ['SynchronizationFieldSearch' => ['synchronizationEntityId' => $model->id]]
            )
        );

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Synchronization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Synchronization();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Synchronization model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Synchronization model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Synchronization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Synchronization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Synchronization::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFull($id/*, $type = 'list', $params = []*/)//TODO Перенести в пост
    {
        $request = yii::$app->request;
        $model = new FormFullSync();

        if ($model->load($request->post()) && $model->validate()) {
            $modelSync = $this->findModel($model->entityId);

            $jobId = $modelSync->addJobFull($model->method, $model->dateTimeStart);
            return $this->redirect(['view', 'id' => $model->entityId]);
        }

        if ($request->post('action') != 'submit') {
            $model->entityId = $id;
        }

        return $this->render('full', [
            'model' => $model,
        ]);




        return $jobId;//TODO подумать про вьюху
    }

    public function actionActivate($id = null)
    {
        $request = yii::$app->request;
        $model = new FormSync();

        if ($model->load($request->post()) && $model->validate()) {
            $modelSync = $this->findModel($model->entityId);
            $modelSync->activate($model->period);
            return $this->redirect(['view', 'id' => $model->entityId]);
        }

        if ($request->post('action') != 'submit') {
            $model->period = 1;
            $model->entityId = $id;
        }

        return $this->render('activate', [
            'model' => $model,
        ]);
    }

    public function actionNoActivate($id)
    {
        $model = $this->findModel($id);
        $model->activate();
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionCreateTable($id)
    {
        $model = $this->findModel($id);
        $model->CreateTable();
        return $this->redirect(['view', 'id' => $model->id]);
    }
}
