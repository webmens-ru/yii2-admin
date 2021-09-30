<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\placements\Placement */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Встройка', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'placementDirectoryModel' => $placementDirectoryModel,
    ]) ?>

</div>
