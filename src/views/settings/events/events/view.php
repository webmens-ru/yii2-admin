<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\events\Events */

$this->title = $model->event->description;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="events-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                        'method' => 'post',
                    ],
                ])
?>
            </p>
        </div>
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Установить на портал', ['b24-install', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Удалить с портала', ['b24-delete', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
            </p>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'event_name',
            'handler',
            'auth_type',
            'event_type',
        ],
    ]) ?>

</div>
