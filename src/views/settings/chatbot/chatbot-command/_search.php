<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotCommandSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chatbot-command-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'bot_code') ?>

    <?= $form->field($model, 'command') ?>

    <?= $form->field($model, 'common') ?>

    <?= $form->field($model, 'hidden') ?>

    <?= $form->field($model, 'extranet_support') ?>

    <?php // echo $form->field($model, 'title_ru') ?>

    <?php // echo $form->field($model, 'params_ru') ?>

    <?php // echo $form->field($model, 'title_en') ?>

    <?php // echo $form->field($model, 'params_en') ?>

    <?php // echo $form->field($model, 'event_command_add') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
