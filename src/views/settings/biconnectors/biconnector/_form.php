<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\Biconnector $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="biconnector-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'urlCheck')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'urlTableList')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'urlTableDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'urlData')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'bx24Id')->textInput() ?>

    <?= $form->field($model, 'isSystem')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
