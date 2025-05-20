<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorTables $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="biconnector-tables-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->widget(Select2::className(), [
        'data' => $tables,
        'options' => ['placeholder' => 'Выберите таблицу...'],
        'pluginOptions' => [
        ],
    ]);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
