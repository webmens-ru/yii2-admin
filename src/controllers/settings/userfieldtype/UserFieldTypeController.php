<?php

namespace wm\admin\controllers\settings\userfieldtype;

use Yii;
use wm\admin\models\settings\userfieldtype\UserFieldType;
use wm\admin\models\settings\userfieldtype\UserFieldTypeSearch;
use yii\base\BaseObject;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserFieldTypeController implements the CRUD actions for UserFieldType model.
 */
class UserFieldTypeController extends \wm\admin\controllers\BaseModuleController
{
    /**
     * Lists all UserFieldType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserFieldTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserFieldType model.
     * @param integer $id
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
     * Creates a new UserFieldType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserFieldType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserFieldType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserFieldType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionB24List()
    {
        $result = UserFieldType::getB24List();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => false,
        ]);

        Yii::warning(ArrayHelper::toArray($dataProvider), '$dataProvider');

        return $this->render('b24-list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionB24Delete($id)
    {
        $model = $this->findModel($id);
        $model->removeBitrix24();
        Yii::$app->session->setFlash('success', "Удаление с портала выполнено!");
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionB24Install($id)
    {
        $model = $this->findModel($id);
        $model->toBitrix24();
        Yii::$app->session->setFlash('success', "Установка на портал выполнена!");
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the UserFieldType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserFieldType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserFieldType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
