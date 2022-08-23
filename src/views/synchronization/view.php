<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Synchronization */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Настройки'];
$this->params['breadcrumbs'][] = ['label' => 'Синхронизация', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="synchronization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!--        --><? //= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
        if ($model->isTable()) {
            if ($model->active) {
                echo Html::a('Выключить синхронизацию', ['no-activate', 'id' => $model->id], ['class' => 'btn btn-danger']);
            } else {
                echo Html::a('Включить синхронизацию', ['activate', 'id' => $model->id], ['class' => 'btn btn-success']);
            }

            echo Html::a(
                'Выполнить полную синхронизацию',
                ['full', 'id' => $model->id],
                [
                    'class' => 'btn btn-primary',
//                    'title' => 'Выполнить полную синхронизацию',
//                    'aria-label' => 'Выполнить полную синхронизацию',
//                    'data-pjax' => 0,
//                    'data-confirm' => 'Вы уверены, что хотите выполнить полную синхронизацию?',
//                    'data-method' => 'post'
                ]
            );
        } else {
            echo Html::a('Создать таблицу', ['create-table', 'id' => $model->id], ['class' => 'btn btn-success']);
        }


        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label' => $model->getAttributeLabel('active'),
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox(
                        'active[]',
                        $model->active,
                        ['disabled' => true]
                    );
                },
            ],
            [
                'label' => 'В Битрикс24',
                'attribute' => 'inB24',
            ],
            [
                'label' => 'В базе данных',
                'attribute' => 'inDb',
            ],
        ],
    ]) ?>
    <?php
    if ($model->isTable()) {
        ?>
        <div class="synchronization-field-index">

            <h1>Поля</h1>

            <p>
                <?= Html::a('Добавить', ['/admin/synchronization/synchronization-fields/create', 'synchronizationEntityId' => $model->id], ['class' => 'btn btn-success']) ?>
            </p>

            <?php // echo $this->render('_search', ['model' => $searchModel]);
            ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',

                    'name',
                    'title',
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'Действия',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{view}{delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a(
                                    '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
                                    ['synchronization/synchronization-fields/view', 'id' => $key],
                                    ['class' => 'glyphicon glyphicon-eye-open']);
                            },
                            'delete' => function ($url, $model, $key) {
                                if (!$model->noDelete) {
                                    return Html::a(
                                        '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
                                        ['synchronization/synchronization-fields/delete', 'id' => $key],
                                        [
//                                    'class' => 'glyphicon glyphicon-trash',
                                            'title' => 'Удалить',
                                            'aria-label' => 'Удалить',
                                            'data-pjax' => 0,
                                            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                            'data-method' => 'post'
                                        ]
                                    );
                                }
                            },
                        ],
                    ],
                ],
            ]); ?>

        </div>

        <?php
    }
    ?>
