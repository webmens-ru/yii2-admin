<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\Biconnector $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Biconnectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="biconnector-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?php
                if (!$model->isSystem) {
                    echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить данный коннектор?',
                            'method' => 'post',
                        ],
                    ]);
                } else {
                    echo Html::a(
                        'Таблицы',
                        null,
                        [
                            'class' => 'btn btn-primary',
                            'href' => \yii\helpers\Url::to('/admin/settings/biconnectors/biconnector-tables/index')
                        ]
                    );

                    echo Html::a(
                        'Токены',
                        null,
                        [
                            'class' => 'btn btn-primary',
                            'href' => \yii\helpers\Url::to('/admin/settings/biconnectors/biconnector-tokens/index')
                        ]
                    );
                }

                ?>

            </p>
        </div>
        <div class="btn-group" role="group" aria-label="First group">
            <p>
                <?php
                if ($model->bx24Id) {
                    echo Html::a(
                        'Удалить с портала',
                        ['b24-delete', 'id' => $model->id],
                        ['class' => 'btn btn-danger']
                    );
                } else {
                    echo Html::a(
                        'Установить на портал',
                        ['install', 'id' => $model->id],
                        ['class' => 'btn btn-success']
                    );
                }
                ?>
            </p>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'logo:ntext',
            'description',
            'urlCheck',
            'urlTableList',
            'urlTableDescription',
            'urlData',
            'sort',
            'bx24Id',
            'isSystem',
        ],
    ]) ?>

    <div class="biconnectors-settings-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

        <p>
            <?= Html::a(
                'Создать',
                ['settings/biconnectors/biconnector-settings/create', 'biconnectorId' => $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        </p>

        <?=
        \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                'type',
                'code',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'headerOptions' => ['width' => '80'],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a(
                                '',
                                [
                                    'settings/biconnectors/biconnector-settings/update',
                                    'biconnectorId' => $model->biconnectorId,
                                    'code' => $model->code,
                                ],
                                ['class' => 'fas fa-edit']
                            );
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '',
                                [
                                    'settings/biconnectors/biconnector-settings/view',
                                    'biconnectorId' => $model->biconnectorId,
                                    'code' => $model->code,
                                ],
                                ['class' => 'fas fa-eye']
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '',
                                [
                                    'settings/biconnectors/biconnector-settings/delete',
                                    'biconnectorId' => $model->biconnectorId,
                                    'code' => $model->code,
                                ],
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
        ]);
        ?>
    </div>

</div>
