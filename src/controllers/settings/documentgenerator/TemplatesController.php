<?php

namespace wm\admin\controllers\settings\documentgenerator;

use Yii;
use wm\admin\models\settings\documentgenerator\Templates;
use wm\admin\models\settings\documentgenerator\TemplatesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * TemplatesController implements the CRUD actions for Templates model.
 */
class TemplatesController extends \wm\admin\controllers\BaseModuleController
{
    /**
     * Lists all Templates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Templates model.
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($code)
    {
        return $this->render('view', [
                    'model' => $this->findModel($code),
        ]);
    }

    /**
     * Creates a new Templates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Templates();
        $request = Yii::$app->request;

//        if ($model->load($request->post())) {
//            $model->file = UploadedFile::getInstance($model, 'file');
//        }

        if ($model->load($request->post()) && $model->save()) {
            // //            if ($model->file) {
//                $model->file->saveAs('uploads/' . $model->code . '-'
// . $model->file->baseName . '.' . $model->file->extension);
//                $model->file_path = 'uploads/' . $model->code . '-'
// . $model->file->baseName . '.' . $model->file->extension;
//                $model->file = null;
//            }


            return $this->redirect(['view', 'code' => $model->code]);
        }

        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $regions = Templates::getRegionsList($b24App);
        $numerators = Templates::getNumeratorsList($b24App);



        if ($request->post('action') != 'submit') {
            $model->active = 'Y';
            $model->with_stamps = 'N';
            $model->region_id = 'ru';
            $model->sort = 500;
        }

        return $this->render('create', [
                    'model' => $model,
                    'regions' => $regions,
                    'numerators' => $numerators,
        ]);
    }

    /**
     * Updates an existing Templates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $model = $this->findModel($code);
        $request = Yii::$app->request;

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'code' => $model->code]);
        }

//        if ($model->load($request->post())) {
//            $model->file = UploadedFile::getInstance($model, 'file');
//        }
//
//        if ($model->validate()) {
//            if ($model->file) {
//                $model->file->saveAs('uploads/' . $model->code . '-'
// . $model->file->baseName . '.' . $model->file->extension);
//                $model->file_path = 'uploads/' . $model->code . '-'
// . $model->file->baseName . '.' . $model->file->extension;
//                $model->file = null;
//            }
//            $model->save(false);
//
//            return $this->redirect(['view', 'code' => $model->code]);
//        }

        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $regions = Templates::getRegionsList($b24App);
        $numerators = Templates::getNumeratorsList($b24App);

        return $this->render('update', [
                    'model' => $model,
                    'regions' => $regions,
                    'numerators' => $numerators,
        ]);
    }

    /**
     * Deletes an existing Templates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($code)
    {
        $model = $this->findModel($code);
        if (is_file($model->file_path)) {
            unlink($model->file_path); // delete file
        }
        $model->delete();
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
    public function actionInstall($code)
    {
        $model = $this->findModel($code);
        $model->toBitrix24();
        return $this->render('install', [
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
    public function actionB24Delete($code)
    {
        $model = $this->findModel($code);
        $model->removeBitrix24();
        return $this->render('delete', [
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
        $result = \yii\helpers\ArrayHelper::getValue(Templates::getB24List(), 'result.templates');
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
    public function actionB24Fields($code)
    {
        $model = $this->findModel($code);
        $fielsds = $model->fieldsBitrix24();
        $arrFields = [];
        foreach ($fielsds['templateFields'] as $key => $value) {
            $value['name'] = $key;
            $arrFields[] = $value;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $arrFields,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $this->render('b24-fields', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Templates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code
     * @return Templates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = Templates::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
