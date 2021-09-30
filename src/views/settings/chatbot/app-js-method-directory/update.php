<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\AppJsMethodDirectory */

$this->title = 'Изменение: ' . $model->title;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'js методы приложений чатботов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->code]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="app-js-method-directory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
