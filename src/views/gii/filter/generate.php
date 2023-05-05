<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\gii\filter\Form */

$this->title = 'Фильтр';
$this->params['breadcrumbs'][] = 'gii';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="settings-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'tableName')->widget(Select2::className(), [
            'data' => $tables,
            'options' => ['placeholder' => 'Выберите таблицу...'],
            'pluginOptions' => [
            ],
        ]);
        ?>

        <?= $form->field($model, 'entityCode')->widget(Select2::className(), [
            'data' => $entities,
            'options' => ['placeholder' => 'Выберите Entity...'],
            'pluginOptions' => [
            ],
        ]);
        ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
