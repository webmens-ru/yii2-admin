<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\Robots */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Роботы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
