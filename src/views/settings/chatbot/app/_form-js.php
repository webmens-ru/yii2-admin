<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\App */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bot_code')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'js_method_code')->dropDownList(ArrayHelper::map($jsMethods, 'code', 'title')) ?>

    <?= $form->field($model, 'js_param')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon_file')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contex_code')->dropDownList(ArrayHelper::map($contects, 'code', 'title')) ?>

    <?= $form->field($model, 'extranet_support')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

    <?= $form->field($model, 'iframe_popup')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'livechat_support')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>
    
    <?= $form->field($model, 'type')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
