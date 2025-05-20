<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\Biconnector $model */

$this->title = 'Update Biconnector: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Biconnectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="biconnector-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
