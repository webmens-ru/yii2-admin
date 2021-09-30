<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\RobotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список установленных событий';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="b24-list">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>Онлайн события</h2>
    <?=
    GridView::widget([
        'dataProvider' => $onlineDataProvider,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'event',
            'handler',
            'auth_type',
        ],
    ]);
    ?>
    <h2>Оффлайн события</h2>
    <?=
    GridView::widget([
        'dataProvider' => $offlineDataProvider,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'event',
            'connector_id',
        ],
    ]);
    ?>
</div>
