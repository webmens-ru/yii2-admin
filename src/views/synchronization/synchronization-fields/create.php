<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */

$this->title = 'Добавление поля';
$this->params['breadcrumbs'][] = ['label' => 'Синхронизация', 'url' => ['/admin/synchronization/index']];
$this->params['breadcrumbs'][] = ['label' => 'Сущность', 'url' => ['/admin/synchronization/view', 'id' => $model->synchronizationEntityId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="synchronization-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'b24Fields' => $b24Fields,
    ]) ?>

</div>
