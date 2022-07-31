<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\settings\placements\PlacementDirectorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник встроек';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-directory-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name_id',
            'description',
            [
                'attribute' => 'category_name',
                'content' => function ($data) {
                    return $data->category_name;
                },
                'filter' => ArrayHelper::map($placementsCategories, 'name', 'name'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('', ['update', 'id' => $key], ['class' => 'fas fa-edit']);
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('', ['view', 'id' => $key], ['class' => 'fas fa-eye']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '',
                            ['delete', 'id' => $key],
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

    <?php Pjax::end(); ?>

</div>
