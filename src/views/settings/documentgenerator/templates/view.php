<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\documentgenerator\Templates */

$this->title = $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = 'Генератор документов';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="templates-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-toolbar justify-content-between" role="toolbar">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Изменить', ['update', 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a('Удалить', ['delete', 'code' => $model->code], [
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
                <?= Html::a(
                    'Установить на портал',
                    ['install', 'code' => $model->code],
                    ['class' => 'btn btn-success']
                ) ?>
                <?= Html::a(
                    'Обновить на портале',
                    ['b24-update', 'code' => $model->code],
                    ['class' => 'btn btn-primary']
                ) ?>
                <?= Html::a(
                    'Удалить с портала',
                    ['b24-delete', 'code' => $model->code],
                    ['class' => 'btn btn-danger']
                ) ?>
                <?= Html::a(
                    'Поля шаблона',
                    ['b24-fields', 'code' => $model->code],
                    ['class' => 'btn btn-primary']
                ) ?>
            </p>
        </div>
    </div>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'file_path',
            'numerator_id',
            'region_id',
            'code',
            'active',
            'with_stamps',
            'sort',
        ],
    ])
    ?>

</div>
