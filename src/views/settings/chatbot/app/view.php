<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\App */

$this->title = $model->bot_code;
$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="app-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?= Html::a('Изменить', ['update', 'bot_code' => $model->bot_code, 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
                <?=
                Html::a('Удалить', ['delete', 'bot_code' => $model->bot_code, 'code' => $model->code], [
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
                <?= Html::a('Установить на портал', ['install', 'bot_code' => $model->bot_code, 'code' => $model->code], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Обновить на портале', ['b24-update', 'bot_code' => $model->bot_code, 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить с портала', ['b24-delete', 'bot_code' => $model->bot_code, 'code' => $model->code], ['class' => 'btn btn-danger']) ?>
            </p>
        </div>
    </div>


    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bot_code',
            'code',
            'js_method_code',
            'js_param',
            'icon_file:ntext',
            'contex_code',
            'extranet_support',
            'iframe_popup',
            'title_ru',
            'title_en',
            'iframe',
            'iframe_height',
            'iframe_width',
            'hash',
            'hidden',
            'livechat_support',
        ],
    ])
    ?>

</div>
