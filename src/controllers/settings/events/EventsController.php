<?php

namespace wm\admin\controllers\settings\events;

use Yii;
use wm\admin\models\settings\events\Events;
use wm\admin\models\settings\events\EventsSearch;
use wm\admin\models\settings\events\EventsDirectory;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends \wm\admin\controllers\BaseModuleController {

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
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new EventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $eventsNameModel = EventsDirectory::find()->all();
        $eventsType = EventsDirectory::$EVENT_TYPES;

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'eventsNameModel' => $eventsNameModel,
                    'eventsType' => $eventsType,
        ]);
    }

    public function actionB24List() {
        $result = Events::getB24EventsList()['result'];
        $resultOnline = array_filter($result, 'wm\b24tools\b24Tools::isEventOnline');
        $resultOffline = array_filter($result, 'wm\b24tools\b24Tools::isEventOffline');
        $onlineDataProvider = new ArrayDataProvider([
            'allModels' => $resultOnline,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        $offlineDataProvider = new ArrayDataProvider([
            'allModels' => $resultOffline,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $this->render('b24-list', [
                    'onlineDataProvider' => $onlineDataProvider,
                    'offlineDataProvider' => $offlineDataProvider,
        ]);
    }

    public function actionB24Delete($id) {
        $model = $this->findModel($id);
        $model->removeBitrix24();
        return $this->render('b24-delete', [
                    'model' => $model
        ]);
    }

    public function actionB24Install($id) {
        $model = $this->findModel($id);
        $model->toBitrix24();
        return $this->render('b24-install', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single Events model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Events();
        $eventsNameModel = EventsDirectory::find()->all();
        $eventsType = EventsDirectory::$EVENT_TYPES;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
                    'eventsNameModel' => $eventsNameModel,
                    'eventsType' => $eventsType,
        ]);
    }

    /**
     * Updates an existing Events model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $eventsNameModel = EventsDirectory::find()->all();
        $eventsType = EventsDirectory::$EVENT_TYPES;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'eventsNameModel' => $eventsNameModel,
                    'eventsType' => $eventsType,
        ]);
    }

    /**
     * Deletes an existing Events model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
