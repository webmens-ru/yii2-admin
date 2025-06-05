<?php

use wm\admin\models\settings\biconnectors\BiconnectorTables;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorTablesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Biconnector Tables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-tables-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Biconnector Tables', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'title',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, BiconnectorTables $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'name' => $model->name]);
                }
            ],
        ],
    ]); ?>


</div>
