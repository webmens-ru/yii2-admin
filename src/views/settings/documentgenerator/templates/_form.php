<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\documentgenerator\Templates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="templates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?php if ($model->file_path): ?> 
        <a href="<?= '/web/' . $model->file_path ?>" target="_blank">Сохраненный файл</a>
        </br>
    <?php endif; ?>                                                            


    <?=
    $form->field($model, 'file')->widget(FileInput::classname(), [
    //'options' => ['accept' => 'image/*'],
    ]);
    ?>

    <?= $form->field($model, 'numerator_id')->dropDownList($numerators)
    ?>

    <?= $form->field($model, 'region_id')->dropDownList($regions) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

    <?= $form->field($model, 'with_stamps')->dropDownList(['Y' => 'Да', 'N' => 'Нет']) ?>

        <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
