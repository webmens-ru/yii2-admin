<?php

namespace wm\admin\controllers\settings\chatbot;

use Yii;
use wm\admin\models\settings\chatbot\Chatbot;
use wm\admin\models\settings\chatbot\ChatbotColorDirectory;
use wm\admin\models\settings\chatbot\ChatbotTypeDirectory;
use wm\admin\models\settings\chatbot\ChatbotSearch;
use wm\admin\models\settings\chatbot\ChatbotCommandSearch;
use wm\admin\models\settings\chatbot\AppSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;

/**
 * ChatbotController implements the CRUD actions for Chatbot model.
 */
class ChatbotController extends \wm\admin\controllers\BaseModuleController
{
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
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($code)
    {
        $model = $this->findModel($code);

        $searchModel = new ChatbotCommandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->code);

        $AppSearchModel = new AppSearch();
        $AppDataProvider = $AppSearchModel->search(
            array_merge(Yii::$app->request->queryParams, ['AppCode' => $model->code])
        );

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
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $model = $this->findModel($code);
        $colors = ChatbotColorDirectory::find()->all();
        $typies = ChatbotTypeDirectory::find()->all();
        $openLineList = Chatbot::getOpenLineList();

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
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($code)
    {
        $this->findModel($code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param string $code
     * @return string
     * @throws NotFoundHttpException
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionB24Delete($code)
    {
        $model = $this->findModel($code);
        $model->removeBitrix24();
        return $this->render('delete', [
                    'model' => $model
        ]);
    }

    /**
     * @param string $code
     * @return string
     * @throws NotFoundHttpException
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionB24Update($code)
    {
        $model = $this->findModel($code);
        $model->updateBitrix24();
        return $this->render('b24-update', [
                    'model' => $model
        ]);
    }

    /**
     * @param string $code
     * @return string
     * @throws NotFoundHttpException
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionInstall($code)
    {
        $model = $this->findModel($code);
        $model->toBitrix24();
        return $this->render('install', [
                    'model' => $model
        ]);
    }

    /**
     * @return string
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionB24List()
    {
        $result = Chatbot::getB24List()['result'];
//        $resultOnline = array_filter($result, 'wm\b24tools\b24Tools::isEventOnline');
//        $resultOffline = array_filter($result, 'wm\b24tools\b24Tools::isEventOffline');
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
     * @param string $code
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
