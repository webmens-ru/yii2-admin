<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorTables $model */

$this->title = 'Update Biconnector Tables: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Biconnector Tables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'name' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="biconnector-tables-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tables' => $tables,
    ]) ?>

</div>
