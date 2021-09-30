<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chatbot-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'openline') ?>

    <?= $form->field($model, 'p_name') ?>

    <?= $form->field($model, 'p_last_name') ?>

    <?php // echo $form->field($model, 'p_color_name') ?>

    <?php // echo $form->field($model, 'p_email') ?>

    <?php // echo $form->field($model, 'p_personal_birthday') ?>

    <?php // echo $form->field($model, 'p_work_position') ?>

    <?php // echo $form->field($model, 'p_personal_www') ?>

    <?php // echo $form->field($model, 'p_personal_gender') ?>

    <?php // echo $form->field($model, 'p_personal_photo_url') ?>

    <?php // echo $form->field($model, 'event_handler') ?>

    <?php // echo $form->field($model, 'event_massege_add') ?>

    <?php // echo $form->field($model, 'event_massege_update') ?>

    <?php // echo $form->field($model, 'event_massege_delete') ?>

    <?php // echo $form->field($model, 'event_welcome_massege') ?>

    <?php // echo $form->field($model, 'event_bot_delete') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
