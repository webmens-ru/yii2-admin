<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wm\admin\models\settings\chatbot\Chatbot */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = 'Настройки';
$this->params['breadcrumbs'][] = ['label' => 'Чатботы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chatbot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'colors' => $colors,
        'typies' => $typies,
        'openLineList' => $openLineList,
    ])
    ?>

</div>
