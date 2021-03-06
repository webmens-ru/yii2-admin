<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\RobotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список установленных чатботов';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="b24-list">
    <h1><?= Html::encode($this->title) ?></h1>    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'BOT_ID ',
            'NAME',
            'CODE',
            'OPENLINE',
        ],
    ]);
    ?>    
</div>
