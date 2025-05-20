<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\Biconnector $model */

$this->title = 'Create Biconnector';
$this->params['breadcrumbs'][] = ['label' => 'Biconnectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
