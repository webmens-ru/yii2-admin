<?php

namespace wm\admin\controllers\settings\chatbot;

use Yii;
use wm\admin\models\settings\chatbot\ChatbotCommand;
use wm\admin\models\settings\chatbot\ChatbotCommandSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ChatbotCommandController implements the CRUD actions for ChatbotCommand model.
 */
class ChatbotCommandController extends \wm\admin\controllers\BaseModuleController
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
     * Lists all ChatbotCommand models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChatbotCommandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChatbotCommand model.
     * @param string $bot_code
     * @param string $command
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($bot_code, $command)
    {
        return $this->render('view', [
            'model' => $this->findModel($bot_code, $command),
        ]);
    }

    /**
     * Creates a new ChatbotCommand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($chatbotCode)
    {
        $request = Yii::$app->request;
        $model = new ChatbotCommand();

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'bot_code' => $model->bot_code, 'command' => $model->command]);
        }
        
        if ($request->post('action') != 'submit') {            
            $model->bot_code = $chatbotCode;
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ChatbotCommand model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $bot_code
     * @param string $command
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($bot_code, $command)
    {
        $model = $this->findModel($bot_code, $command);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'bot_code' => $model->bot_code, 'command' => $model->command]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ChatbotCommand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $bot_code
     * @param string $command
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($bot_code, $command)
    {
        $this->findModel($bot_code, $command)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionB24Delete($bot_code, $command) {
        $model = $this->findModel($bot_code, $command);
        $model->removeBitrix24();
        return $this->render('delete', [
                    'model' => $model
        ]);
    }

    public function actionInstall($bot_code, $command) {
        $model = $this->findModel($bot_code, $command);
        $model->toBitrix24();
        return $this->render('install', [
                    'model' => $model
        ]);
    }
    
    public function actionB24Update($bot_code, $command) {
        $model = $this->findModel($bot_code, $command);
        $model->updateBitrix24();
        return $this->render('b24-update', [
                    'model' => $model
        ]);
    }

    /**
     * Finds the ChatbotCommand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $bot_code
     * @param string $command
     * @return ChatbotCommand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bot_code, $command)
    {
        if (($model = ChatbotCommand::findOne(['bot_code' => $bot_code, 'command' => $command])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
