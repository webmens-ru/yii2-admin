<?php

use app\assets\AppAsset;
use app\widgets\Alert;
use wm\admin\assets\ModuleAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

$assetsUrl = ModuleAsset::register($this);
AppAsset::register($this);
$this->registerJsFile($assetsUrl->baseUrl . '/js/application.js');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="app">

    <div class="wrap">
        <?php
        NavBar::begin(
            [
                'brandLabel' => Html::img($assetsUrl->baseUrl . '/img/logo.png', ['class' => 'logo']),
                'brandUrl' => '/admin',
                'options' => [
                    'class' => 'navbar navbar-expand-lg navbar-dark bg-dark shadow p-3 mb-5',
                ],
            ]
        );

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => [
                [
                    'label' => 'Настройки',
                    'items' => [
                        ['label' => 'Общие настройки', 'url' => '/admin/settings/index'],
                        ['label' => 'Роботы', 'url' => '/admin/settings/robots/robots/index'],
                        ['label' => 'События', 'url' => '/admin/settings/events/events/index'],
                        ['label' => 'Справочник событий', 'url' => '/admin/settings/events/events-directory/index'],
                        ['label' => 'Встраивание', 'url' => '/admin/settings/placements/placement/index'],
                        [
                            'label' => 'Справочник мест встраивания',
                            'url' => '/admin/settings/placements/placement-directory/index'
                        ],
                        ['label' => 'Чатботы', 'url' => '/admin/settings/chatbot/chatbot/index'],
                        ['label' => 'Агенты', 'url' => '/admin/settings/agents/index'],
                        [
                            'label' => 'Справочник типов чатботов',
                            'url' => '/admin/settings/chatbot/chatbot-type-directory/index'
                        ],
                        [
                            'label' => 'Справочник цветов чатботов',
                            'url' => '/admin/settings/chatbot/chatbot-color-directory/index'
                        ],
                        ['label' => 'Команды чатботов', 'url' => '/admin/settings/chatbot/chatbot-command/index'],
                        [
                            'label' => 'Генератор документов',
                            'url' => '/admin/settings/documentgenerator/templates/index'
                        ],
                        [
                            'label' => 'Синхронизация',
                            'url' => '/admin/synchronization/index'
                        ],
                        [
                            'label' => 'Смарт-процессвы - Генератор таблиц',
                            'url' => '/admin/gii/smart-process/table-generator'],
                        ['label' => 'Сделки - Генератор таблиц', 'url' => '/admin/gii/deal/table-generator'
                        ],
                        ['label' => 'Лиды - Генератор таблиц', 'url' => '/admin/gii/lead/table-generator'],
                        ['label' => 'Контакты - Генератор таблиц', 'url' => '/admin/gii/contact/table-generator'],
                        ['label' => 'Компании - Генератор таблиц', 'url' => '/admin/gii/company/table-generator'],
                    ],
                ],
                Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?=
            Breadcrumbs::widget([
                'homeLink' => ['label' => 'Приложение', 'url' => ['/admin/base/index']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'shadow-sm p-3 mb-5',
                ],
            ])
            ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="text">&copy; <a target="_blank" href="https://webmens.ru">Webmens</a> <?= date('Y') ?></p>
        </div>
    </footer>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
