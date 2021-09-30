<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\placements\PlacementDirectory */

$this->title = 'Изменение: ' . $model->name_id;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Справочник мест встраивания', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_id, 'url' => ['view', 'id' => $model->name_id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="placement-directory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
