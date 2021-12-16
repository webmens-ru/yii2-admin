<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\Chatbot */

$this->title = $model->code;
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'js методы приложений чатботов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chatbot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Изменит', ['update', 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a('Удалить', ['delete', 'code' => $model->code], [
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
                <?= Html::a('Установить на портал', ['install', 'code' => $model->code], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Обновить на портале', ['b24-update', 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить с портала', ['b24-delete', 'code' => $model->code], ['class' => 'btn btn-danger']) ?>
            </p>
        </div>
    </div>





    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'type_id',
            'openline',
            'p_name',
            'p_last_name',
            'p_color_name',
            'p_email:email',
            'p_personal_birthday',
            'p_work_position',
            'p_personal_www',
            'p_personal_gender',
            'p_personal_photo_url:url',
            'event_handler',
            'event_massege_add',
            'event_massege_update',
            'event_massege_delete',
            'event_welcome_massege',
            'event_bot_delete',
        ],
    ])
    ?>


    <div class="chatbot-command-index">

        <h1><?= Html::encode('Команды') ?></h1>

        <p>
            <?= Html::a('Создать', ['settings/chatbot/chatbot-command/create', 'chatbotCode' => $model->code], ['class' => 'btn btn-success']) ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'bot_code',
                'command',
                'common',
                'hidden',
                //'extranet_support',
                'title_ru',
                //'params_ru',
                //'title_en',
                //'params_en',
                //'event_command_add', 
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'headerOptions' => ['width' => '80'],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('', ['settings/chatbot/chatbot-command/update', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'fas fa-edit']);
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a('', ['settings/chatbot/chatbot-command/view', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'fas fa-eye']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('', ['settings/chatbot/chatbot-command/delete', 'bot_code' => $model->bot_code, 'command' => $model->command], ['class' => 'fas fa-trash', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?', 'data-method' => 'post']);
                        },
                    ],
                ]
            ],
        ]);
        ?>

    </div>

    <div class="app-index">

        <h1><?= Html::encode('Приложения') ?></h1>

        <p>
            <?= Html::a('Создать JS приложение', ['settings/chatbot/app/create', 'chatbotCode' => $model->code, 'type' => 'js'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Создать Ifarame приложение', ['settings/chatbot/app/create', 'chatbotCode' => $model->code, 'type' => 'iframe'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?=
        GridView::widget([
            'dataProvider' => $AppDataProvider,
            'filterModel' => $AppSearchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'bot_code',
                'code',
                'js_method_code',
                'js_param',
                'icon_file:ntext',
                //'contex_code',
                //'extranet_support',
                //'iframe_popup',
                //'title_ru',
                //'title_en',
                //'iframe',
                //'iframe_height',
                //'iframe_width',
                //'hash',
                //'hidden',
                //'livechat_support',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'headerOptions' => ['width' => '80'],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('', ['settings/chatbot/app/update', 'bot_code' => $model->bot_code, 'code' => $model->code], ['class' => 'fas fa-edit']);
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a('', ['settings/chatbot/app/view', 'bot_code' => $model->bot_code, 'code' => $model->code], ['class' => 'fas fa-eye']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('', ['settings/chatbot/app/delete', 'bot_code' => $model->bot_code, 'command' => $model->code], ['class' => 'fas fa-trash', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?', 'data-method' => 'post']);
                        },
                    ],
                ]
            ],
        ]);
        ?>

    </div>
</div>



