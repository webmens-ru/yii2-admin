<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\RobotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Роботы';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robots-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Загрузить из файла', ['file-import'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'handler',
            'auth_user_id',
            'name',
            [
                'attribute' => 'use_subscription',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($searchModel, $index, $widget) {
                    return Html::checkbox(
                        'use_subscription[]',
                        $searchModel->use_subscription,
                        ['value' => $index, 'disabled' => true]
                    );
                },
            ],
            [
                'attribute' => 'use_placement',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($searchModel, $index, $widget) {
                    return Html::checkbox(
                        'use_placement[]',
                        $searchModel->use_placement,
                        ['value' => $index, 'disabled' => true]
                    );
                },
            ],
            //'use_subscription',
            //'use_placement',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', ['update', 'code' => $key], ['class' => 'fas fa-edit']);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('', ['view', 'code' => $key], ['class' => 'fas fa-eye']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '',
                            ['delete', 'code' => $key],
                            [
                                'class' => 'fas fa-trash',
                                'title' => 'Удалить',
                                'aria-label' => 'Удалить',
                                'data-pjax' => 0,
                                'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                'data-method' => 'post'
                            ]
                        );
                    },
                ],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
