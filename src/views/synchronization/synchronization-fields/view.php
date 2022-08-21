<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Настройки'];
$this->params['breadcrumbs'][] = ['label' => 'Синхронизация', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->synchronizationEntity->title,
    'url' => ['/admin/synchronization/view', 'id' => $model->synchronizationEntityId]
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="synchronization-fields-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'title',
            [
                'attribute' => 'synchronizationEntityId',
                'label' => 'Сущность',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->synchronizationEntity->title;
                }
            ],
//            'synchronizationEntityId',
        ],
    ]) ?>

