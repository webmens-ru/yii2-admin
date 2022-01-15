<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\Robots */

$this->title = $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="robots-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Изменить', ['update', 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a('Удалить', ['delete', 'code' => $model->code], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                        'method' => 'post',
                    ],
                ])
                ?>
                <?= Html::a('Экспортировать', ['export', 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
            </p>
        </div>
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Установить на портал', ['install', 'code' => $model->code], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Удалить с портала', ['b24-delete', 'code' => $model->code], ['class' => 'btn btn-danger']) ?>
            </p>
        </div>
    </div>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'handler',
            'auth_user_id',
            'name',
            [
                'label' => $model->getAttributeLabel('use_subscription'),
                'attribute' => 'use_subscription',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox('use_subscription[]', $model->use_subscription, [/*'value' => $index,*/ 'disabled' => true]);
                },
            ],
                [
                'label' => $model->getAttributeLabel('use_placement'),
                'attribute' => 'use_placement',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox('use_placement[]', $model->use_placement, [/*'value' => $index, */'disabled' => true]);
                },
            ],
        ],
    ])
    ?>

    <div class="robots-properties-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

        <p>
            <?= Html::a('Создать', ['settings/robots/robots-properties/create', 'robotCode' => $model->code], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                    'attribute' => 'is_in',
                    'format' => 'raw',
                    'filter' => false,
                    'value' => function ($searchModel, $index, $widget) {
                        return Html::checkbox('is_in[]', $searchModel->is_in, [/*'value' => $index,*/ 'disabled' => true]);
                    },
                ],
                'system_name',
                'name',
                'description',
                    [
                    'attribute' => 'type_id',
                    'content' => function($data) {
                        return $data->getTypeName();
                    },
                    'filter' => ArrayHelper::map($robotsTypesModel, 'id', 'name'),
                ],
                    [
                    'attribute' => 'required',
                    'format' => 'raw',
                    'filter' => false,
                    'value' => function ($searchModel, $index, $widget) {
                        return Html::checkbox('required[]', $searchModel->required, [/*'value' => $index,*/ 'disabled' => true]);
                    },
                ],
                    [
                    'attribute' => 'multiple',
                    'format' => 'raw',
                    'filter' => false,
                    'value' => function ($searchModel, $index, $widget) {
                        return Html::checkbox('multiple[]', $searchModel->multiple, [/*'value' => $index,*/ 'disabled' => true]);
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
                            return Html::a('', ['settings/robots/robots-properties/update', 'robot_code' => $model->robot_code, 'system_name' => $model->system_name], ['class' => 'fas fa-edit']);
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a('', ['settings/robots/robots-properties/view', 'robot_code' => $model->robot_code, 'system_name' => $model->system_name], ['class' => 'fas fa-eye']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('', ['settings/robots/robots-properties/delete', 'robot_code' => $model->robot_code, 'system_name' => $model->system_name], ['class' => 'fas fa-trash', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?', 'data-method' => 'post']);
                        },
                    ],
                ],
            ],
        ]);
        ?>
    </div>

</div>
