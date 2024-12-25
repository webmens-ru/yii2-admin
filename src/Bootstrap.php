<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace wm\admin;

use Yii;
use yii\base\BootstrapInterface;

/**
 *
 */
class Bootstrap implements BootstrapInterface
{
    //Метод, который вызывается автоматически при каждом запросе
    /**
     * @param mixed $app
     * @return void
     */
    public function bootstrap($app)
    {
        //Правила маршрутизации
        $app->getUrlManager()->addRules([
            'admin' => 'admin/base/index',
        ], false);
        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле frontend/config/main.php
         */
         $app->setModule('admin', [
            'class' => 'wm\admin\module',
         ]);
    }
}
