<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\ChatbotCommand */

$this->title = $model->command;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Чатботы', 'url' => ['settings/chatbot/chatbot/index']];
$this->params['breadcrumbs'][] = ['label' => $model->bot_code, 'url' => ['settings/chatbot/chatbot/view', 'code' => $model->bot_code]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chatbot-command-view">

    <h1><?= Html::encode($this->title) ?></h1>    

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Изменить', ['update', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a('Удалить', ['delete', 'bot_code' => $model->bot_code, 'command' => $model->command], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                        'method' => 'post',
                    ],
                ])
                ?>
            </p>
        </div>
        <div class="btn-group" role="group" aria-label="First group">
            <p>
<?= Html::a('Установить на портал', ['install', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'btn btn-success']) ?>
<?= Html::a('Переустановить', ['b24-update', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'btn btn-success']) ?>                
<?= Html::a('Удалить с портала', ['b24-delete', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'btn btn-danger']) ?>
            </p>
        </div>
    </div>



    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bot_code',
            'command',
            'common',
            'hidden',
            'extranet_support',
            'title_ru',
            'params_ru',
            'title_en',
            'params_en',
            'event_command_add',
            'command_id',
        ],
    ])
    ?>

</div>
