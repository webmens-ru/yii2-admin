<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создать';
//$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sp-table-create">
    <?php
    if ($error) {
        echo '<div class="alert alert-danger" role="alert">' . Html::encode($error) . '</div>';
    }
    ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="robots-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'tableName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'deleteOldTable')->checkbox() ?>

        <?= $form->field($model, 'tableFields[]')->dropDownList($fieldsList, [
            'multiple' => 'multiple',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>