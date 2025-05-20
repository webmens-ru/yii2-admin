<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SynchronizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Синхронизация';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="synchronization-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'table',
//            'active',
            [
                'attribute' => 'active',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($searchModel, $index, $widget) {
                    return Html::checkbox(
                        'active[]',
                        $searchModel->active,
                        ['value' => $index, 'disabled' => true]
                    );
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view}', //{update} {delete}
            ],
        ],
    ]); ?>
</div>
