<?php

namespace wm\admin\controllers\synchronization;

use wm\admin\models\Synchronization;
use wm\admin\models\synchronization\SynchronizationField;
use wm\admin\models\synchronization\SynchronizationFieldForm;
use wm\admin\models\synchronization\SynchronizationFieldSearch;
use Yii;
use yii\filters\VerbFilter;

/**
 * SynchronizationController implements the CRUD actions for Synchronization model.
 */
class SynchronizationFieldsController extends \wm\admin\controllers\BaseModuleController
{
    /**
     * @inheritDoc
     */
    public function behaviors()//TODO права
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

//    /**
//     * Lists all Synchronization models.
//     *
//     * @return string
//     */
//    public function actionIndex()
//    {
//        $searchModel = new SynchronizationSearch();
//        $dataProvider = $searchModel->search($this->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }
//
    /**
     * Displays a single Synchronization model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Synchronization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($synchronizationEntityId)
    {
        $modelSynchronization = Synchronization::find()->where(['id' => $synchronizationEntityId])->one();
        $b24Fields = $modelSynchronization->getB24FieldsList();
        $model = new SynchronizationFieldForm();
        $request = Yii::$app->request;

        if ($model->load($this->request->post()) && $model->validate()) {
            $model->addField();
            if(!$model->errors){
                return $this->redirect(['/admin/synchronization/view', 'id' => $model->synchronizationEntityId]);
            }
            
        }

        if ($request->post('action') != 'submit') {
            $model->synchronizationEntityId = $synchronizationEntityId;
        }

        return $this->render('create', [
            'model' => $model,
            'b24Fields' => $b24Fields,
        ]);
    }

//    /**
//     * Updates an existing Synchronization model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param int $id ID
//     * @return string|\yii\web\Response
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }
//
    /**
     * Deletes an existing Synchronization model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        $synchronizationEntityId = $model->synchronizationEntityId;
        $model->delete();

        return $this->redirect(['/admin/synchronization/view', 'id' => $synchronizationEntityId]);
    }

    /**
     * Finds the Synchronization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SynchronizationField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SynchronizationField::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
//
//    public function actionFull($id, $type = 'list', $params = [])//TODO Перенести в пост
//    {
//        $model = $this->findModel($id);
//        $id = $model->addJobFull($type, $params);
//        return $id;//TODO подумать про вьюху
//    }
}
