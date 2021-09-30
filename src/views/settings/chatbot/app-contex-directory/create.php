<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\AppContexDirectory */

$this->title = 'Create App Contex Directory';
$this->params['breadcrumbs'][] = ['label' => 'App Contex Directories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-contex-directory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
