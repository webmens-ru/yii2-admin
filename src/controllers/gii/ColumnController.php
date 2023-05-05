<?php

namespace wm\admin\controllers\gii;

use wm\admin\models\gii\Column;
use wm\admin\models\ui\Entity;
use wm\yii\helpers\ArrayHelper;
use Yii;


class ColumnController extends \wm\admin\controllers\BaseModuleController
{
    public function actionGenerate()
    {
        $model = new Column();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->generate();
            Yii::$app->session->setFlash('success', "Генерация выполнена!");
            return $this->redirect(['generate']);
        }
        $tableNames = $model->getTableNames();
        $tables = array_combine($tableNames, $tableNames);
        $entityNames = ArrayHelper::getColumn(Entity::find()->all(), 'code');
        $entities = array_combine($entityNames, $entityNames);


        return $this->render('generate', [
            'model' => $model,
            'tables' => $tables,
            'entities' => $entities
        ]);
    }
}
