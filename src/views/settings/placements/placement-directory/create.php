<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\placements\PlacementDirectory */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Справочник мест встраивания', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-directory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'placementsCategories' => $placementsCategories,
    ]) ?>

</div>
