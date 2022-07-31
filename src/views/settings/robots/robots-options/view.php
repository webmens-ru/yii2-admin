<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsOptions */

$this->title = $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['settings/robots/robots/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->property->robot->name,
    'url' => ['settings/robots/robots/view', 'code' => $model->robot_code]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->property->name,
    'url' => [
        'settings/robots/robots-properties/view',
        'robot_code' => $model->robot_code,
        'system_name' => $model->property_name
    ]
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="robots-options-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(
            'Изменить',
            [
                'update',
                'property_name' => $model->property_name,
                'robot_code' => $model->robot_code,
                'value' => $model->value
            ],
            ['class' => 'btn btn-primary']
        ) ?>
        <?=
        Html::a(
            'Удалить',
            [
                'delete',
                'property_name' => $model->property_name,
                'robot_code' => $model->robot_code,
                'value' => $model->value
            ],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'method' => 'post',
                ],
            ]
        )
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'property_name',
            'robot_code',
            'value',
            'name',
            'sort',
        ],
    ])
    ?>

</div>
