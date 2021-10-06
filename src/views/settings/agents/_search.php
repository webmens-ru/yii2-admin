<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\baseapp\models\settings\AgentsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agents-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'class') ?>

    <?= $form->field($model, 'method') ?>

    <?= $form->field($model, 'params') ?>

    <?php // echo $form->field($model, 'date_run') ?>

    <?php // echo $form->field($model, 'period') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
