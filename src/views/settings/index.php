<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name_id',
            'value',
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', ['update', 'id' => $key], ['class' => 'fas fa-edit']);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('', ['view', 'id' => $key], ['class' => 'fas fa-eye']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', ['delete', 'id' => $key], ['class' => 'fas fa-trash', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?', 'data-method' => 'post']);
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
