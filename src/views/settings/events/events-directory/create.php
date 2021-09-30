<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\events\EventsDirectory */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Справочник событий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-directory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'eventsCategories' => $eventsCategories,
        
    ]) ?>

</div>
