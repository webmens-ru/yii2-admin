<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\settings\chatbot\ChatbotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Чатботы';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chatbot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать чатбота', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Список чатботов', ['b24-list'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'type_id',
            'openline',
            'p_name',
            'p_last_name',
            //'p_color_name',
            //'p_email:email',
            //'p_personal_birthday',
            //'p_work_position',
            //'p_personal_www',
            //'p_personal_gender',
            //'p_personal_photo_url:url',
            //'event_handler',
            //'event_massege_add',
            //'event_massege_update',
            //'event_massege_delete',
            //'event_welcome_massege',
            //'event_bot_delete',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', ['update', 'code' => $key], ['class' => 'fas fa-edit']);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('', ['view', 'code' => $key], ['class' => 'fas fa-eye']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '',
                            ['delete', 'code' => $key],
                            [
                                'class' => 'fas fa-trash',
                                'title' => 'Удалить',
                                'aria-label' => 'Удалить',
                                'data-pjax' => 0,
                                'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                'data-method' => 'post'
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
