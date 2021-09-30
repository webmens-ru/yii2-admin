<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\AppSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bot_code') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'js_method_code') ?>

    <?= $form->field($model, 'js_param') ?>

    <?= $form->field($model, 'icon_file') ?>

    <?php // echo $form->field($model, 'contex_code') ?>

    <?php // echo $form->field($model, 'extranet_support') ?>

    <?php // echo $form->field($model, 'iframe_popup') ?>

    <?php // echo $form->field($model, 'title_ru') ?>

    <?php // echo $form->field($model, 'title_en') ?>

    <?php // echo $form->field($model, 'iframe') ?>

    <?php // echo $form->field($model, 'iframe_height') ?>

    <?php // echo $form->field($model, 'iframe_width') ?>

    <?php // echo $form->field($model, 'hash') ?>

    <?php // echo $form->field($model, 'hidden') ?>

    <?php // echo $form->field($model, 'livechat_support') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
