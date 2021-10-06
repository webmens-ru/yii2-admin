<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\baseapp\models\settings\Agents */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agents-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
