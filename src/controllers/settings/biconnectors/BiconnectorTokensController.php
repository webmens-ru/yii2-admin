<?php

namespace wm\admin\controllers\settings\biconnectors;

use wm\admin\models\settings\biconnectors\BiconnectorTokens;
use wm\admin\models\settings\biconnectors\BiconnectorTokensSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;


/**
 *
 */
class BiconnectorTokensController extends \wm\admin\controllers\BaseModuleController
{

    /**
     * @return mixed[]
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
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BiconnectorTokensSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        BiconnectorTokens::generateToken();
        return $this->redirect(['index']);

    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRefresh($id)
    {
        $model = $this->findModel($id);
        $model->refreshToken();
        return $this->redirect(['index']);
    }


    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * @param int $id
     * @return BiconnectorTokens
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = BiconnectorTokens::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
