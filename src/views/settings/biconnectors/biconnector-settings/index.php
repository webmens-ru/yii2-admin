<?php

use wm\admin\models\settings\biconnectors\BiconnectorSettings;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var wm\admin\models\settings\biconnectors\BiconnectorSettingsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Biconnector Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biconnector-settings-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Biconnector Settings', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'biconnectorId',
            'name',
            'type',
            'code',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, BiconnectorSettings $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'biconnectorId' => $model->biconnectorId, 'code' => $model->code]);
                }
            ],
        ],
    ]); ?>


</div>
