<?php

namespace wm\admin\controllers\settings\biconnectors;

use wm\admin\models\settings\biconnectors\Biconnector;
use wm\admin\models\settings\biconnectors\BiconnectorSearch;
use wm\admin\models\settings\biconnectors\BiconnectorSettingsSearch;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * BiconnectorController implements the CRUD actions for Biconnector model.
 */
class BiconnectorController extends \wm\admin\controllers\BaseModuleController
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
     * Lists all Biconnector models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BiconnectorSearch();
        $request = Yii::$app->request;
        $dataProvider = $searchModel->search($request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Biconnector model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new BiconnectorSettingsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Biconnector model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Biconnector();
        $request = Yii::$app->request;

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
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
     * Updates an existing Biconnector model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Biconnector model.
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
     * Finds the Biconnector model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Biconnector the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Biconnector::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param int $id
     * @return \yii\web\Response
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
    public function actionB24Delete($id)
    {
        $model = $this->findModel($id);
        $model->removeBitrix24();
        Yii::$app->session->setFlash('success', "Коннектор успешно удалён с портала!");
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
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
    public function actionInstall($id)
    {
        $model = $this->findModel($id);
        $model->toBitrix24();
        Yii::$app->session->setFlash('success', "Коннектор установлен!");
        return $this->redirect(['view', 'id' => $model->id]);
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
        $result = Biconnector::getB24List()['result'];
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $this->render('b24-list', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
