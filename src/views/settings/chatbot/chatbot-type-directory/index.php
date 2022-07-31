<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\settings\chatbot\ChatbotTypeDirectorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник типов чатботов';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chatbot-type-directory-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'title',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', ['update', 'name' => $key], ['class' => 'fas fa-edit']);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('', ['view', 'name' => $key], ['class' => 'fas fa-eye']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '',
                            ['delete', 'name' => $key],
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
