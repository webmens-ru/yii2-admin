<?php

namespace wm\admin\controllers\settings\chatbot;

use Yii;
use wm\admin\models\settings\chatbot\Chatbot;
use wm\admin\models\settings\chatbot\ChatbotColorDirectory;
use wm\admin\models\settings\chatbot\ChatbotTypeDirectory;
use wm\admin\models\settings\chatbot\ChatbotSearch;
use yii\web\Controller;
use wm\admin\models\settings\chatbot\ChatbotCommandSearch;
use wm\admin\models\settings\chatbot\AppSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * ChatbotController implements the CRUD actions for Chatbot model.
 */
class ChatbotController extends Controller
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
     * Lists all Chatbot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChatbotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Chatbot model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($code)
    {
        $model = $this->findModel($code);
        
        $searchModel = new ChatbotCommandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->code);
        
        $AppSearchModel = new AppSearch();
        $AppDataProvider = $AppSearchModel->search(Yii::$app->request->queryParams, $model->code);
        
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'AppSearchModel' => $AppSearchModel,
            'AppDataProvider' => $AppDataProvider,
        ]);
    }

    /**
     * Creates a new Chatbot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Chatbot();
        
        $colors = ChatbotColorDirectory::find()->all();
        $typies = ChatbotTypeDirectory::find()->all();
        $openLineList = Chatbot::getOpenLineList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'code' => $model->code]);
        }

        return $this->render('create', [
            'model' => $model,
            'colors' => $colors,
            'typies' => $typies,
            'openLineList' => $openLineList,
        ]);
    }

    /**
     * Updates an existing Chatbot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $model = $this->findModel($code);
        $colors = ChatbotColorDirectory::find()->all();
        $typies = ChatbotTypeDirectory::find()->all();
        $openLineList = Chatbot::getOpenLineList();
        //Yii::warning($openLineList, '$openLineListC');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'code' => $model->code]);
        }

        return $this->render('update', [
            'model' => $model,
            'colors' => $colors,
            'typies' => $typies,
            'openLineList' => $openLineList,
        ]);
    }

    /**
     * Deletes an existing Chatbot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($code)
    {
        $this->findModel($code)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionB24Delete($code) {
        $model = $this->findModel($code);
        $model->removeBitrix24();
        return $this->render('delete', [
                    'model' => $model
        ]);
    }
    
    public function actionB24Update($code) {
        $model = $this->findModel($code);
        $model->updateBitrix24();
        return $this->render('b24-update', [
                    'model' => $model
        ]);
    }

    public function actionInstall($code) {
        $model = $this->findModel($code);
        $model->toBitrix24();
        return $this->render('install', [
                    'model' => $model
        ]);
    }
    
    public function actionB24List() {
        $result = Chatbot::getB24List()['result'];
//        $resultOnline = array_filter($result, 'app\components\b24Tools::isEventOnline');
//        $resultOffline = array_filter($result, 'app\components\b24Tools::isEventOffline');
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
//        $offlineDataProvider = new ArrayDataProvider([
//            'allModels' => $resultOffline,
//            'pagination' => [
//                'pageSize' => 30,
//            ],
//        ]);
        return $this->render('b24-list', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Chatbot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Chatbot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = Chatbot::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
