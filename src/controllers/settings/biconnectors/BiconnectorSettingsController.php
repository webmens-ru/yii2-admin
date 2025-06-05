<?php

namespace wm\admin\controllers\settings\biconnectors;

use wm\admin\models\settings\biconnectors\BiconnectorSettings;
use wm\admin\models\settings\biconnectors\BiconnectorSettingsSearch;
use wm\admin\models\settings\biconnectors\BiconnectorSettingsType;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * BiconnectorSettingsController implements the CRUD actions for BiconnectorSettings model.
 */
class BiconnectorSettingsController extends \wm\admin\controllers\BaseModuleController
{
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
     * Lists all BiconnectorSettings models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BiconnectorSettingsSearch();
        $request = Yii::$app->request;
        $dataProvider = $searchModel->search($request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BiconnectorSettings model.
     * @param int $biconnectorId Biconnector ID
     * @param string $code Code
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($biconnectorId, $code)
    {
        return $this->render('view', [
            'model' => $this->findModel($biconnectorId, $code),
        ]);
    }

    /**
     * Creates a new BiconnectorSettings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $biconnectorId
     * @return string|\yii\web\Response
     */
    public function actionCreate($biconnectorId)
    {
        $model = new BiconnectorSettings();
        $settingTypesModel = BiconnectorSettingsType::find()->all();
        $request = Yii::$app->request;

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'biconnectorId' => $model->biconnectorId, 'code' => $model->code]);
        }

        if ($request->post('action') != 'submit') {
            $model->biconnectorId = $biconnectorId;
        }

        return $this->render('create', [
            'model' => $model,
            'settingTypesModel' => $settingTypesModel,
        ]);
    }

    /**
     * Updates an existing BiconnectorSettings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $biconnectorId Biconnector ID
     * @param string $code Code
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($biconnectorId, $code)
    {
        $model = $this->findModel($biconnectorId, $code);
        $settingTypesModel = BiconnectorSettingsType::find()->all();
        $request = Yii::$app->request;

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'biconnectorId' => $model->biconnectorId, 'code' => $model->code]);
        }

        return $this->render('update', [
            'model' => $model,
            'settingTypesModel' => $settingTypesModel,
        ]);
    }

    /**
     * Deletes an existing BiconnectorSettings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $biconnectorId Biconnector ID
     * @param string $code Code
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($biconnectorId, $code)
    {
        $this->findModel($biconnectorId, $code)->delete();

        return $this->redirect(['settings/biconnectors/biconnector/view', 'id' => $biconnectorId]);
//        return $this->redirect(['index']);
    }

    /**
     * Finds the BiconnectorSettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $biconnectorId Biconnector ID
     * @param string $code Code
     * @return BiconnectorSettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($biconnectorId, $code)
    {
        if (($model = BiconnectorSettings::findOne(['biconnectorId' => $biconnectorId, 'code' => $code])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
