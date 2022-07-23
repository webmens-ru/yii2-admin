<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsProperties */

$this->title = $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['settings/robots/robots/index']];
$this->params['breadcrumbs'][] = ['label' => $model->robot->name, 'url' => ['settings/robots/robots/view', 'code' => $model->robot_code]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="robots-properties-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'robot_code' => $model->robot_code, 'system_name' => $model->system_name], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Удалить', ['delete', 'robot_code' => $model->robot_code, 'system_name' => $model->system_name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
                [
                'label' => $model->getAttributeLabel('robot_code'),
                'value' => $model->robotName,
                ],
                [
                'label' => $model->getAttributeLabel('is_in'),
                'attribute' => 'is_in',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox('is_in[]', $model->is_in, ['value' => $index, 'disabled' => true]);
                },
                ],
                'system_name',
                'name',
                'description',
                [
                'label' => $model->getAttributeLabel('type_id'),
                'value' => $model->typeName,
                ],
                [
                'label' => $model->getAttributeLabel('required'),
                'attribute' => 'required',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox('required[]', $model->required, ['value' => $index, 'disabled' => true]);
                },
                ],
                [
                'label' => $model->getAttributeLabel('multiple'),
                'attribute' => 'multiple',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox('multiple[]', $model->multiple, ['value' => $index, 'disabled' => true]);
                },
                ],
                'default',
                'sort',
        ],
    ])
    ?>
    <?php
    if ($model->type->name == 'select_static') {
        ?>
        <div class="robots-options-index">

            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

            <p>
                <?=
                Html::a('Создать', [
                    'settings/robots/robots-options/create',
                    'robotCode' => $model->robot_code,
                    'propertyName' => $model->system_name], [
                    'class' => 'btn btn-success'])
                ?>
            </p>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                    'value',
                    'name',
                    'sort',
                        [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Действия',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('', ['settings/robots/robots-options/update', 'property_name' => $model->property_name, 'robot_code' => $model->robot_code, 'value' => $model->value], ['class' => 'fas fa-edit']);
                            },
                            'view' => function ($url, $model, $key) {
                                return Html::a('', ['settings/robots/robots-options/view', 'property_name' => $model->property_name, 'robot_code' => $model->robot_code, 'value' => $model->value], ['class' => 'fas fa-eye']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('', ['settings/robots/robots-options/delete', 'property_name' => $model->property_name, 'robot_code' => $model->robot_code, 'value' => $model->value], [
                                            'class' => 'fas fa-trash',
                                            'title' => 'Удалить',
                                            'aria-label' => 'Удалить',
                                            'data-pjax' => 0,
                                            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                            'data-method' => 'post']);
                            },
                        ],
                        ],
                ],
            ]);
            ?>
        </div>
    <?php } ?>

</div>
