<?php

namespace wm\admin\controllers\settings\events;

use Yii;
use wm\admin\models\settings\events\EventsDirectory;
use wm\admin\models\settings\events\EventsDirectorySearch;
use wm\admin\models\settings\events\EventsCategories;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventsDirectoryController implements the CRUD actions for EventsDirectory model.
 */
class EventsDirectoryController extends Controller
{
    public $layout = 'admin.php';
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all EventsDirectory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventsDirectorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $eventsCategories = EventsDirectory::$CATEGORIES;
        $eventsType = EventsDirectory::$EVENT_TYPES;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'eventsCategories' => $eventsCategories,
            'eventsType' => $eventsType,
        ]);
    }

    /**
     * Displays a single EventsDirectory model.
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
     * Creates a new EventsDirectory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EventsDirectory();
        $eventsCategories = EventsDirectory::$CATEGORIES;
        $eventsType = EventsDirectory::$EVENT_TYPES;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('create', [
            'model' => $model,
            'eventsCategories' => $eventsCategories,
            
        ]);
    }

    /**
     * Updates an existing EventsDirectory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $eventsCategories = EventsDirectory::$CATEGORIES;
        $eventsType = EventsDirectory::$EVENT_TYPES;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', [
            'model' => $model,
            'eventsCategories' => $eventsCategories,
            'eventsType' => $eventsType,
        ]);
    }

    /**
     * Deletes an existing EventsDirectory model.
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
     * Finds the EventsDirectory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EventsDirectory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventsDirectory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
