<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsProperties */

$this->title = 'Создать параметр';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['settings/robots/robots/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->robot->name,
    'url' => ['settings/robots/robots/view', 'code' => $model->robot_code]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robots-properties-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'robotsTypesModel' => $robotsTypesModel,
    ]) ?>

</div>
