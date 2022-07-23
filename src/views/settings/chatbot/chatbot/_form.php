<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

//use yii;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\Chatbot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chatbot-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>    
    
    <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map($typies, 'name', 'title')) ?>

    <?= $form->field($model, 'openline')->dropDownList($openLineList, ['prompt' => 'Нет']) ?>
    
    <?= $form->field($model, 'p_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'p_last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'p_color_name')->dropDownList(ArrayHelper::map($colors, 'name', 'title')) ?>

    <?= $form->field($model, 'p_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'p_personal_birthday')->widget(DatePicker::className(), ['dateFormat' => 'yyyy-MM-dd',]) ?>

    <?= $form->field($model, 'p_work_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'p_personal_www')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'p_personal_gender')->textInput() ?>

    <?= $form->field($model, 'p_personal_photo_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_handler')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_massege_add')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_massege_update')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_massege_delete')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_welcome_massege')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_bot_delete')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
