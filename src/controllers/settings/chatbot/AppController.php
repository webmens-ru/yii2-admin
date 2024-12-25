<?php

namespace wm\admin\controllers\settings\chatbot;

use Yii;
use wm\admin\models\settings\chatbot\App;
use wm\admin\models\settings\chatbot\AppContexDirectory;
use wm\admin\models\settings\chatbot\AppJsMethodDirectory;
use wm\admin\models\settings\chatbot\AppSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AppController implements the CRUD actions for App model.
 */
class AppController extends \wm\admin\controllers\BaseModuleController
{
    /**
     * Lists all App models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single App model.
     * @param string $bot_code
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($bot_code, $code)
    {
        return $this->render('view', [
                    'model' => $this->findModel($bot_code, $code),
        ]);
    }

    /**
     * Creates a new App model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $chatbotCode
     * @param string|null $type
     * @return mixed
     */
    public function actionCreate($chatbotCode, $type = null)
    {
        $request = Yii::$app->request;

        $model = new App();

        $jsMethods = AppJsMethodDirectory::find()->all();
        $contects = AppContexDirectory::find()->all();

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'bot_code' => $model->bot_code, 'code' => $model->code]);
        }

        if (!$type) {
            $type = $model->type;
        }

        if ($request->post('action') != 'submit') {
            $model->bot_code = $chatbotCode;
            $model->type = $type;
            $model->extranet_support = 'N';
            $model->iframe_popup = 'N';
            $model->hidden = 'N';
            $model->livechat_support = 'N';
        }

        return $this->render('create', [
                    'model' => $model,
                    'type' => $model->type,
                    'jsMethods' => $jsMethods,
                    'contects' => $contects,
        ]);
    }

    /**
     * Updates an existing App model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $bot_code
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($bot_code, $code)
    {
        $model = $this->findModel($bot_code, $code);

        $jsMethods = AppJsMethodDirectory::find()->all();
        $contects = AppContexDirectory::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'bot_code' => $model->bot_code, 'code' => $model->code]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'type' => $model->type,
                    'jsMethods' => $jsMethods,
                    'contects' => $contects,
        ]);
    }

    /**
     * Deletes an existing App model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $bot_code
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($bot_code, $code)
    {
        $this->findModel($bot_code, $code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the App model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $bot_code
     * @param string $code
     * @return App the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bot_code, $code)
    {
        if (($model = App::findOne(['bot_code' => $bot_code, 'code' => $code])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param string $bot_code
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
    public function actionInstall($bot_code, $code)
    {
        $model = $this->findModel($bot_code, $code);
        $model->toBitrix24();
        return $this->render('install', [
                    'model' => $model
        ]);
    }

    /**
     * @param string $bot_code
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
    public function actionB24Update($bot_code, $code)
    {
        $model = $this->findModel($bot_code, $code);
        $model->updateBitrix24();
        return $this->render('b24-update', [
                    'model' => $model
        ]);
    }
}
