<?php

use wm\admin\models\settings\biconnectors\BiconnectorTokens;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorTablesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Biconnector Tokens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-tokens-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать токен', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'token',
            'created_at',
            [
                'class' => ActionColumn::className(),
                'header' => 'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{refresh}{delete}',
                'buttons' => [
                    'refresh' => function ($url, $model, $key) {
                        return Html::a(
                            '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns:xlink="http://www.w3.org/1999/xlink" 	 viewBox="0 0 489.698 489.698" xml:space="preserve"><g><g><path d="M468.999,227.774c-11.4,0-20.8,8.3-20.8,19.8c-1,74.9-44.2,142.6-110.3,178.9c-99.6,54.7-216,5.6-260.6-61l62.9,13.1			c10.4,2.1,21.8-4.2,23.9-15.6c2.1-10.4-4.2-21.8-15.6-23.9l-123.7-26c-7.2-1.7-26.1,3.5-23.9,22.9l15.6,124.8			c1,10.4,9.4,17.7,19.8,17.7c15.5,0,21.8-11.4,20.8-22.9l-7.3-60.9c101.1,121.3,229.4,104.4,306.8,69.3			c80.1-42.7,131.1-124.8,132.1-215.4C488.799,237.174,480.399,227.774,468.999,227.774z"/>		<path d="M20.599,261.874c11.4,0,20.8-8.3,20.8-19.8c1-74.9,44.2-142.6,110.3-178.9c99.6-54.7,216-5.6,260.6,61l-62.9-13.1			c-10.4-2.1-21.8,4.2-23.9,15.6c-2.1,10.4,4.2,21.8,15.6,23.9l123.8,26c7.2,1.7,26.1-3.5,23.9-22.9l-15.6-124.8			c-1-10.4-9.4-17.7-19.8-17.7c-15.5,0-21.8,11.4-20.8,22.9l7.2,60.9c-101.1-121.2-229.4-104.4-306.8-69.2			c-80.1,42.6-131.1,124.8-132.2,215.3C0.799,252.574,9.199,261.874,20.599,261.874z"/>	</g></g></svg>',
                            ['refresh', 'id' => $key],
                            ['class' => 'glyphicon glyphicon-eye-open']
                        );
                    },
                    'delete' => function ($url, $model, $key) {
//                        if (!$model->noDelete) {
                            return Html::a(
                                '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
                                ['delete', 'id' => $key],
                                [
                                    'title' => 'Удалить',
                                    'aria-label' => 'Удалить',
                                    'data-pjax' => 0,
                                    'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                    'data-method' => 'post'
                                ]
                            );
//                        }
                    },
                ],
            ],
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, BiconnectorTokens $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
        ],
    ]); ?>


</div>
