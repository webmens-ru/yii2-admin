<?php

namespace wm\admin\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle
{
    public $baseUrl = '@web';
    public $sourcePath = '@vendor/webmens-ru/yii2-admin/src/assets/web/'; //---
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'js/application.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
