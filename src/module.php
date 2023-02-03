<?php

namespace wm\admin;

/**
 * b24 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'wm\admin\controllers';

    //private $moduleName;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        \Yii::configure($this, require __DIR__ . '/config.php');
        $this->layout = 'main';
    }
}
