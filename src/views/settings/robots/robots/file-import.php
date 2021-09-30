<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\Robots */

$this->title = 'Импорт из файла';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robots-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="robots-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'file')->fileInput() ?>


        <div class="form-group">
            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
