<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotCommand */

$this->title = 'Update Chatbot Command: ' . $model->bot_code;
$this->params['breadcrumbs'][] = ['label' => 'Chatbot Commands', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bot_code, 'url' => ['view', 'bot_code' => $model->bot_code, 'command' => $model->command]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chatbot-command-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
