<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use wm\admin\assets\ModuleAsset;

$assetsUrl = ModuleAsset::register($this);
AppAsset::register($this);
$this->registerJsFile($assetsUrl->baseUrl . '/js/application.js');

$script = <<< JS
    $(document).ready(function () {

        BX24.init(function(){
                app.saveFrameWidth();

        });
        frame = BX24.getScrollSize();
        BX24.resizeWindow(frame.scrollWidth, frame.scrollHeight);
    });
JS;
//Маркир конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="//api.bitrix24.com/api/v1/"></script>
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
                            'brandUrl' => '/admin/admin-base/index',
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
                                    ['label' => 'Справочник мест встраивания', 'url' => '/admin/settings/placements/placement-directory/index'],
                                    ['label' => 'Чатботы', 'url' => '/admin/settings/chatbot/chatbot/index'],
                                    ['label' => 'Агенты', 'url' => '/admin/settings/agents/index'],
                                    ['label' => 'Справочник типов чатботов', 'url' => '/admin/settings/chatbot/chatbot-type-directory/index'],
                                    ['label' => 'Справочник цветов чатботов', 'url' => '/admin/settings/chatbot/chatbot-color-directory/index'],
                                    ['label' => 'Команды чатботов', 'url' => '/admin/settings/chatbot/chatbot-command/index'],
                                    ['label' => 'Генератор документов', 'url' => '/admin/settings/documentgenerator/templates/index'],
                                    //['label' => 'Справочник js методов приложений чатботов', 'url' => '/web/' . Yii::getAlias('@moduleName') . '/settings/chatbot/app-js-method-directory/index'],
                                    //['label' => 'Справочник типов контекста приложений чатботов', 'url' => '/web/' . Yii::getAlias('@moduleName') . '/settings/chatbot/app-contex-directory/index'],
                            ],
                        ],
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
