<?php

namespace wm\admin\models;

use Bitrix24\Im\Im;
//use Cassandra\Date;
use Yii;
use yii\base\Model;
use wm\b24tools\b24Tools;
use Bitrix24\B24Object;
use yii\helpers\ArrayHelper;
use wm\admin\models\B24ConnectSettings;

//use wm\admin\models\B24ConnectSettings;
//use wm\admin\models\Settings;
//use yii\helpers\ArrayHelper;

class ChatbotHelpDeskHelper extends Model {

//    public static function b24Connect($auth) {
//        $component = new b24Tools();
//        $b24App = $component->connect(
//                B24ConnectSettings::getParametrByName('applicationId'),
//                B24ConnectSettings::getParametrByName('applicationSecret'),
//                B24ConnectSettings::getParametrByName('b24PortalName'),
//                null,
//                null,
//                $auth);
//        return $b24App;
//    }

    public function MassegeAdd() {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $data = $request->post('data');
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromChat(
                B24ConnectSettings::getParametrByName('applicationId'),
                B24ConnectSettings::getParametrByName('applicationSecret'),
                $auth
        );
        $obB24 = new Im($b24App);
        $b24 = $obB24->client->call('im.chat.user.list', ['CHAT_ID' => ArrayHelper::getValue($data, 'PARAMS.CHAT_ID')]);
    }

    public function MassegeWelcome() {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $data = $request->post('data');
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromChat(
                B24ConnectSettings::getParametrByName('applicationId'),
                B24ConnectSettings::getParametrByName('applicationSecret'),
                $auth
        );
        $obB24 = new Im($b24App);
        $b24 = $obB24->client->call('im.chat.user.list', ['CHAT_ID' => ArrayHelper::getValue($data, 'PARAMS.CHAT_ID')]);
    }

}
