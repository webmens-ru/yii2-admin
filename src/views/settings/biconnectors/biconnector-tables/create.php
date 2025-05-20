<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorTables $model */

$this->title = 'Create Biconnector Tables';
$this->params['breadcrumbs'][] = ['label' => 'Biconnector Tables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-tables-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tables' => $tables,
    ]) ?>

</div>
