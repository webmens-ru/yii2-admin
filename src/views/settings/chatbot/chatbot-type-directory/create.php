<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotTypeDirectory */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Справочник типов чатботов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chatbot-type-directory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
