<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\events\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="events-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $data = ArrayHelper::map($eventsNameModel, 'name', 'description', 'category_name');
    ?>
    
    <?= $form->field($model, 'event_name')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => 'Выберите событие...'],
//        'theme' => Select2::THEME_MATERIAL,
        'pluginOptions' => [
//            'allowClear' => true
        ],
    ]);
?>

    <?= $form->field($model, 'handler')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_type')->textInput() ?>

        <?= $form->field($model, 'event_type')->dropDownList(ArrayHelper::map($eventsType, 'value', 'name')) ?>
    
    

    <div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
