<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\RobotsProperties */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="robots-properties-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'robot_code')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'is_in')->checkbox() ?>

    <?= $form->field($model, 'system_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map($robotsTypesModel, 'id', 'name')) ?>

    <?= $form->field($model, 'required')->checkbox() ?>

    <?= $form->field($model, 'multiple')->checkbox() ?>

    <?= $form->field($model, 'default')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
