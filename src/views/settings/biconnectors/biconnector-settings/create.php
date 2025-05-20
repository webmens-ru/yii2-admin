<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSettings $model */

$this->title = 'Создание параметра коннектора';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Коннекторы', 'url' => ['settings/biconnectors/biconnector/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->biconnector->title,
    'url' => ['settings/biconnectors/biconnector/view', 'id' => $model->biconnectorId]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'settingTypesModel' => $settingTypesModel,
    ]) ?>

</div>
