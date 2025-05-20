<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\RobotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список установленных коннекторов';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Biconnectors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="b24-list">
    <h1><?= Html::encode($this->title) ?></h1>    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'description',
            'dateCreate',
            'logo',
            'urlCheck',
            'urlTableList',
            'urlTableDescription',
            'urlData',
            'settings',
            'sort',
        ],
    ]);
    ?>    
</div>
