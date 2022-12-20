<?php

use kartik\datetime\DateTimePicker;
use wm\admin\models\synchronization\FormFullSync;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */

$this->title = 'Полная синхронизация';
$this->params['breadcrumbs'][] = ['label' => 'Настройки'];
$this->params['breadcrumbs'][] = ['label' => 'Синхронизация', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Сущность', 'url' => ['view', 'id' => $model->entityId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="synchronization-active">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="synchronization-full-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'entityId')->hiddenInput()->label(false) ?>

        <?=
        $form
            ->field($model, 'method')
            ->dropDownList(ArrayHelper::map(FormFullSync::METHOD_LIST, 'id', 'title'))
            ->label('Метод синхронизации')
        ?>

        <?= $form->field($model, 'dateTimeStart')->widget(DateTimePicker::classname(), [
            'type' => DateTimePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii:00'
            ]
        ])
            ->label('Дата и время старта полной синхронизации'); ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
