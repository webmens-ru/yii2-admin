<?php

use wm\admin\widgets\AgentsTimeSettingsWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */

$this->title = 'Включение синхронизации';
$this->params['breadcrumbs'][] = ['label' => 'Настройки'];
$this->params['breadcrumbs'][] = ['label' => 'Синхронизация', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Сущность', 'url' => ['view', 'id' => $model->entityId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="synchronization-active">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="synchronization-active-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'entityId')->hiddenInput()->label(false) ?>

        <?= AgentsTimeSettingsWidget::widget(['model' => $modelAgentTimeSettings, 'form' => $form]) ?>

        <div class="form-group">
            <?= Html::submitButton(
                'Сохранить',
                ['class' => 'btn btn-success', 'name'=>'action','value' => 'submit']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
