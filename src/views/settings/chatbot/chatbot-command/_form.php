<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotCommand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chatbot-command-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bot_code')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'command')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'common')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

    <?= $form->field($model, 'hidden')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

    <?= $form->field($model, 'extranet_support')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'params_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'params_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_command_add')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
