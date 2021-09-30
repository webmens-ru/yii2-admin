<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="robots-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'handler') ?>

    <?= $form->field($model, 'auth_user_id') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'use_subscription') ?>

    <?php // echo $form->field($model, 'use_placement') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
