<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="synchronization-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->widget(Select2::classname(), [
        'data' => $b24Fields,
        'options' => ['placeholder' => 'Выберите поле'],
//        'theme' => Select2::THEME_MATERIAL,
        'pluginOptions' => [
//            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'synchronizationEntityId')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
