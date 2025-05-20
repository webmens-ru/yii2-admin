<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSettings $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="biconnector-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'biconnectorId')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map($settingTypesModel, 'code', 'title')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
