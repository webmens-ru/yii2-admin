<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\RobotsPropertiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Параметры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robots-properties-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'robot_code',
                'content' => function ($data) {
                    return $data->getRobotName();
                },
                'filter' => ArrayHelper::map($robotsModel, 'id', 'name'),
            ],
            [
                'attribute' => 'is_in',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($searchModel, $index, $widget) {
                    return Html::checkbox(
                        'is_in[]',
                        $searchModel->is_in,
                        ['value' => $index, 'disabled' => true]
                    );
                },
            ],
            'system_name',
            'name',
            'description',
            [
                'attribute' => 'type_id',
                'content' => function ($data) {
                    return $data->getTypeName();
                },
                'filter' => ArrayHelper::map($robotsTypesModel, 'id', 'name'),
            ],
            [
                'attribute' => 'required',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($searchModel, $index, $widget) {
                    return Html::checkbox(
                        'required[]',
                        $searchModel->required,
                        ['value' => $index, 'disabled' => true]
                    );
                },
            ],
            [
                'attribute' => 'multiple',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($searchModel, $index, $widget) {
                    return Html::checkbox(
                        'multiple[]',
                        $searchModel->multiple,
                        ['value' => $index, 'disabled' => true]
                    );
                },
            ],
            //'default',
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
                        return Html::a(
                            '',
                            ['delete', 'id' => $key],
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
