<?php

namespace wm\admin;

use Yii;

/**
 * b24 module definition class
 */
class module extends \yii\base\Module {

    public $controllerNamespace = 'wm\admin\controllers';
    
    private $moduleName;

    public function init() {
        parent::init();
        \Yii::configure($this, require __DIR__ . '/config.php');
        $this->layout = 'main';
        $this->moduleName = 'admin';
        $this->setAliases([
//            '@moduleName' => $this->moduleName,
//            '@webModuleAsset' => '@web' . '/' . $this->moduleName . '/assets/web',
//            '@appModuleLayouts' => '@app' . '/modules/' . $this->moduleName . '/views/layouts',
//            '@webMmoduleApp' => '/' . $this->moduleName,
//            '@appMmoduleApp' => '/modules/' . $this->moduleName ,            
        ]);
    }

}
