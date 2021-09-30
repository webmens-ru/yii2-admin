<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotColorDirectory */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Справочник цветов чатбота', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chatbot-color-directory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
