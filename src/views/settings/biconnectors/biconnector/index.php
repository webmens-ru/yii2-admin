<?php

use wm\admin\models\settings\biconnectors\Biconnector;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Biconnectors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Список коннекторов', ['b24-list'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'logo:ntext',
            'description',
            'urlCheck',
            //'urlTableList',
            //'urlTableDescription',
            //'urlData',
            //'sort',
            //'bx24Id',
            //'isSystem',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Biconnector $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view}', //{update} {delete}
            ],
        ],
    ]); ?>


</div>
