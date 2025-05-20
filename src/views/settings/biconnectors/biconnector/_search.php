<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="biconnector-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'logo') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'urlCheck') ?>

    <?php // echo $form->field($model, 'urlTableList') ?>

    <?php // echo $form->field($model, 'urlTableDescription') ?>

    <?php // echo $form->field($model, 'urlData') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'bx24Id') ?>

    <?php // echo $form->field($model, 'isSystem') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
