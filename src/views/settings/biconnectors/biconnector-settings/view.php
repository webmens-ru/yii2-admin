<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSettings $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Коннекторы', 'url' => ['settings/biconnectors/biconnector/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->biconnector->title,
    'url' => ['settings/biconnectors/biconnector/view', 'id' => $model->biconnectorId]
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="biconnector-settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'biconnectorId' => $model->biconnectorId, 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'biconnectorId' => $model->biconnectorId, 'code' => $model->code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить данный элемент??',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'biconnectorId',
            'name',
            'type',
            'code',
        ],
    ]) ?>

</div>
