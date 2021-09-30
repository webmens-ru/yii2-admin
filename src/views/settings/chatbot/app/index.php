<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\settings\chatbot\AppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create App', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bot_code',
            'code',
            'js_method_code',
            'js_param',
            'icon_file:ntext',
            //'contex_code',
            //'extranet_support',
            //'iframe_popup',
            //'title_ru',
            //'title_en',
            //'iframe',
            //'iframe_height',
            //'iframe_width',
            //'hash',
            //'hidden',
            //'livechat_support',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
