<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\placements\Placement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="placement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $data = ArrayHelper::map($placementDirectoryModel, 'name_id', 'description', 'category_name'); ?>

    <?=
    $form->field($model, 'placement_name')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => 'Выберите встройку...'],
    ]);
    ?>

    <?= $form->field($model, 'entityTypeId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'handler')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'group_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
