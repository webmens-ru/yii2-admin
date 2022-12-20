<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use Yii;

/* @var $this yii\web\View */
/* @var $searchModel wm\admin\models\RobotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список установленных шаблонов';
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
            'name',
            'code',
            [
                'label' => 'Шаблон',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(
                        'Скачать',
                        $data['downloadMachine'],
                        [
                                        'title' => 'Смелей вперед!',
                                        'target' => '_blank'
                                    ]
                    );
                }
            ],
            //'downloadMachine',
            'id',
            'region',
            'withStamps',
            'active'
        ],
    ]);
    ?>    
</div>
