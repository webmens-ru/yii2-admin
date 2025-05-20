<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSettings $model */

$this->title = 'Изменение параметра коннектора: ' . $model->name;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Коннекторы', 'url' => ['settings/biconnectors/biconnector/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->biconnector->title,
    'url' => ['settings/biconnectors/biconnector/view', 'id' => $model->biconnectorId]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->name,
    'url' => [
        'settings/biconnectors/biconnector/view',
        'biconnectorId' => $model->biconnectorId,
        'code' => $model->code
    ]
];
?>
<div class="biconnector-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'settingTypesModel' => $settingTypesModel,
    ]) ?>

</div>
