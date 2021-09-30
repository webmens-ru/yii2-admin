<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsOptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="robots-options-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'property_name')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'robot_code')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
