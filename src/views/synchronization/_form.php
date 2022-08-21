<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="synchronization-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
