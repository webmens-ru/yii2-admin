<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\App */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Чатботы', 'url' => ['settings/chatbot/chatbot/index']];
$this->params['breadcrumbs'][] = ['label' => $model->bot_code, 'url' => ['settings/chatbot/chatbot/view', 'code' => $model->bot_code]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form-' . $type, [
        'model' => $model,
        'jsMethods' => $jsMethods,
        'contects' => $contects,
    ])
    ?>

</div>
