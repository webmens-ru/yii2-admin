<?php

namespace wm\admin\assets;

use yii\web\AssetBundle;

/**
 *
 */
class ModuleAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var string
     */
    public $sourcePath = '@vendor/webmens-ru/yii2-admin/src/assets/web/'; //---
    /**
     * @var string[]
     */
    public $css = [
        'css/style.css',
    ];
    /**
     * @var string[]
     */
    public $js = [
        'js/application.js',
    ];
    /**
     * @var string[]
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    /**
     * @var array<string, mixed>
     */
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
