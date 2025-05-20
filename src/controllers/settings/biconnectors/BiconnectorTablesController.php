<?php

namespace wm\admin\controllers\settings\biconnectors;

use wm\admin\models\settings\biconnectors\BiconnectorTables;
use wm\admin\models\settings\biconnectors\BiconnectorTablesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * BiconnectorTablesController implements the CRUD actions for BiconnectorTables model.
 */
class BiconnectorTablesController extends \wm\admin\controllers\BaseModuleController
{

    /**
     * @return mixed[]|\string[][]|\string[][][][]
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
     * Lists all BiconnectorTables models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BiconnectorTablesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BiconnectorTables model.
     * @param string $name Name
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name)
    {
        return $this->render('view', [
            'model' => $this->findModel($name),
        ]);
    }

    /**
     * Creates a new BiconnectorTables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BiconnectorTables();
        $request = Yii::$app->request;

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'name' => $model->name]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $tableNames = $this->getTableNames();
        $tables = array_combine($tableNames, $tableNames);

        return $this->render('create', [
            'model' => $model,
            'tables' => $tables,
        ]);
    }

    /**
     * Updates an existing BiconnectorTables model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name Name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        $request = Yii::$app->request;

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'name' => $model->name]);
        }

        $tableNames = $this->getTableNames();
        $tables = array_combine($tableNames, $tableNames);

        return $this->render('update', [
            'model' => $model,
            'tables' => $tables,
        ]);
    }

    /**
     * Deletes an existing BiconnectorTables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name Name
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BiconnectorTables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name Name
     * @return BiconnectorTables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = BiconnectorTables::findOne(['name' => $name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return mixed
     */
    public function getTableNames()
    {
        $db = $this->getDbConnection();

        if ($db !== null) {
            return $db->getSchema()->getTableNames();
        }

        return [];
    }

    /**
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     */
    private function getDbConnection()
    {
        return Yii::$app->get('db', false);
    }
}
