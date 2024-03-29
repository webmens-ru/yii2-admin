<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsOptions */

$this->title = 'Изменить: ' . $model->name;
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
$this->params['breadcrumbs'][] = [
    'label' => $model->name,
    'url' => [
        'settings/robots/robots-options/view',
        'property_name' => $model->property_name,
        'robot_code' => $model->robot_code,
        'value' => $model->value
    ]
];
$this->params['breadcrumbs'][] = 'Изменить опцию';
?>
<div class="robots-options-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'robotsPropertiesModel' => $robotsPropertiesModel,
    ]) ?>

</div>
