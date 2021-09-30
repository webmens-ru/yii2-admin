<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsProperties */

$this->title = 'Изменить параметр: ' . $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['settings/robots/robots/index']];
$this->params['breadcrumbs'][] = ['label' => $model->robot->name, 'url' => ['settings/robots/robots/view', 'code' => $model->robot_code]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['settings/robots/robots-properties/view', 'robot_code' => $model->robot_code, 'system_name' => $model->system_name]];
$this->params['breadcrumbs'][] = 'Изменить параметр';
?>
<div class="robots-properties-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'robotsModel' => $robotsModel,
        'robotsTypesModel' => $robotsTypesModel,
    ])
    ?>

</div>
