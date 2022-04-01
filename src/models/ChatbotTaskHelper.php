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

class ChatbotTaskHelper extends Model {

    public static function b24Connect($auth) {
        $component = new b24Tools();
        $b24App = $component->connect(
                B24ConnectSettings::getParametrByName('applicationId'),
                B24ConnectSettings::getParametrByName('applicationSecret'),
                B24ConnectSettings::getParametrByName('b24PortalName'),
                null,
                null,
                $auth);
        return $b24App;
    }

    public function sendMessages($informationTransfer) {
        $session = Yii::$app->session;
        $auth = $session['AccessParams'];
        $b24App = self::b24Connect($auth);
        $obB24 = new Im($b24App);
        $currentUser = $obB24->client->call('user.current', [])['result'];
        $task = $this->getTask($informationTransfer['taskId'], $obB24);
        if ($task['deadline']) {
            $date = new \DateTime($task['deadline']);
            $task['deadline'] = $date->format('d.m.Y H:i:s');
        } else
            $task['deadline'] = '[Крайний срок отсутствует]';
        $deadlineNew = new \DateTime($informationTransfer['deadline']);
        $informationTransfer['deadline'] = $deadlineNew->format('d.m.Y H:i:s');
        $obB24->client->addBatchCall('imbot.message.add',
                [
                    'BOT_ID' => Settings::getParametrByName('chat_bot_id'), // Идентификатор чат-бота, от которого идет запрос, можно не указывать, если чат-бот всего один
                    'DIALOG_ID' => $task['createdBy'], // Идентификатор диалога, это либо USER_ID пользователя, либо chatXX - где XX идентификатор чата, передается в событии ONIMBOTMESSAGEADD и ONIMJOINCHAT
                    'MESSAGE' => '[USER=' . $currentUser['ID'] . ']' . $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'] .
                    '[/USER] запрашивает изменение крайнего срока задачи "' .
                    '[URL=https://' . B24ConnectSettings::getParametrByName('b24PortalName') . '/company/personal/user/' . $task['createdBy'] . '/tasks/task/view/' . $task['id'] . '/]' .
                    $task['title'] . '[/URL]" с [B]' . $task['deadline'] . '[/B] на [B]' . $informationTransfer['deadline'] . '[/B] со следующим комментарием:[B][BR]' . $informationTransfer['comment'] . '[/B]',
                    'URL_PREVIEW' => 'N',
                    'KEYBOARD' => [
                        [
                            'TEXT' => 'Разрешить',
                            'COMMAND' => 'taskDedlineEnable',
                            "COMMAND_PARAMS" => $currentUser['ID'] . '|' . json_encode($informationTransfer) . '|' . $task['createdBy'],
                            'BG_COLOR' => '#9DCF00',
                            'TEXT_COLOR' => '#FFFFFF',
                            'BLOCK' => 'Y',
                            'DISPLAY' => 'LINE'
                        ],
                        [
                            'TEXT' => 'Отклонить',
                            'COMMAND' => 'taskDedlineDisable',
                            "COMMAND_PARAMS" => $currentUser['ID'] . '|' . json_encode($informationTransfer) . '|' . $task['createdBy'],
                            'BG_COLOR' => '#9F1B1F',
                            'TEXT_COLOR' => '#F5e7e7',
                            'BLOCK' => 'Y',
                            'DISPLAY' => 'LINE'
                        ]
                    ]
                ], function ($result) {
            
        });
        
        $obB24->client->addBatchCall('task.commentitem.add', [$task['id'],
            [
                'POST_MESSAGE' => 'Постановщику задачи' .
                ' отправлен запрос о смене крайнего срока задачи с [B]' . $task['deadline'] . '[/B] на [B]' . $deadlineNew->format('d.m.Y H:i:s') .
                '[/B] со следующим комментарием: <br/>[B]' . $informationTransfer['comment'] . '[/B]'
            ]
        ]);
        $obB24->client->processBatchCalls();
    }

    private function getTask($taskId, $obB24) {
        $task = $obB24->client->call('tasks.task.get', ['taskId' => $taskId])['result']['task'];
        return $task;
    }

    public function taskDedlineDisable() {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $b24App = self::b24Connect($auth);
        $properties = $request->post('data');
        $obB24 = new B24Object($b24App);
        $source = 0;
        $information = [];
        $receiver = 0;
        $command = ArrayHelper::getValue($properties, 'COMMAND.' . key($properties["COMMAND"]));
        $commandParams = $command['COMMAND_PARAMS'];
        $commandParams = explode('|', $commandParams);
        $this->placementParams($commandParams, $source, $information, $receiver);
        if ($source == 0 || count($information) == 0 || $receiver == 0) {
            return;
        }
        
        $currentUser = $obB24->client->call('user.current', [])['result'];

        $task = [];
        $obB24->client->addBatchCall(
                'user.get',
                ['ID' => $source],
                function($result) use (&$source) {
            $source = $result['result'][0];
        });
        $obB24->client->addBatchCall(
                'tasks.task.get',
                ['taskId' => $information['taskId']],
                function($result) use (&$task) {
            $task = $result['result']['task'];
        });
        $obB24->client->processBatchCalls();
        $date = new \DateTime($task['deadline']);
        $task['deadline'] = $date->format('d.m.Y');
        $text = '[B]отклонена[/B] постановщиком';
        $obB24->client->addBatchCall('imbot.message.add',
                [
                    'BOT_ID' => $command['BOT_ID'], // Идентификатор чат-бота, от которого идет запрос, можно не указывать, если чат-бот всего один
                    'DIALOG_ID' => $source['ID'], // Идентификатор диалога, это либо USER_ID пользователя, либо chatXX - где XX идентификатор чата, передается в событии ONIMBOTMESSAGEADD и ONIMJOINCHAT
                    'URL_PREVIEW' => 'N',
                    'MESSAGE' => 'Завка на перенос крайнего срока задачи ' .
                    '"[URL=https://' . B24ConnectSettings::getParametrByName('b24PortalName') . '/company/personal/user/' . $task['createdBy'] . '/tasks/task/view/' . $task['id'] . '/]' .
                    $task['title'] . '[/URL]" с [B]' . $task['deadline'] . '[/B] на [B]' . $information['deadline'] . '[/B] ' . $text,
        ]);
        
        $obB24->client->addBatchCall('imbot.message.update',
                [
                    'BOT_ID' => $command['BOT_ID'], // Идентификатор чат-бота, от которого идет запрос, можно не указывать, если чат-бот всего один
                    'MESSAGE_ID' => $command['MESSAGE_ID'],
                    'MESSAGE' => '[USER=' . $currentUser['ID'] . ']' . $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'] .
                    '[/USER] запрашивает изменение крайнего срока задачи "' .
                    '[URL=https://' . B24ConnectSettings::getParametrByName('b24PortalName') . '/company/personal/user/' . $task['createdBy'] . '/tasks/task/view/' . $task['id'] . '/]' .
                    $task['title'] . '[/URL]" с [B]' . $task['deadline'] . '[/B] на [B]' . $information['deadline'] . '[/B] со следующим комментарием:[B][BR]' . $information['comment'] . '[/B] Вы не разрешили перенос срока',
                    'URL_PREVIEW' => 'N',
                    'KEYBOARD' => '',
                ], function ($result) {
            
        });
        
        $obB24->client->addBatchCall('task.commentitem.add', [$task['id'],
            [
                'POST_MESSAGE' => 'Заявка на перенос крайнего срока задачи от ' . $source['NAME'] . ' ' . $source['LAST_NAME'] .
                ' о смене крайнего срока задачи с [B]' . $task['deadline'] . '[/B] на [B]' . $information['deadline'] . '[/B] ' . $text
            ]
        ]);
        $obB24->client->processBatchCalls();
    }

    public function taskDedlineEnable() {
        $request = Yii::$app->request;
        $auth = $request->post('auth');
        $b24App = self::b24Connect($auth);
        $properties = $request->post('data');
        $obB24 = new B24Object($b24App);
        $source = 0;
        $information = [];
        $receiver = 0;
        $command = ArrayHelper::getValue($properties, 'COMMAND.' . key($properties["COMMAND"]));
        $commandParams = $command['COMMAND_PARAMS'];
        $commandParams = explode('|', $commandParams);
        $this->placementParams($commandParams, $source, $information, $receiver);
        if ($source == 0 || count($information) == 0 || $receiver == 0) {
            return;
        }

        $currentUser = $obB24->client->call('user.current', [])['result'];

        $task = [];
        $obB24->client->addBatchCall(
                'user.get',
                ['ID' => $source],
                function($result) use (&$source) {
            $source = $result['result'][0];
        });
        $obB24->client->addBatchCall(
                'tasks.task.get',
                ['taskId' => $information['taskId']],
                function($result) use (&$task) {
            $task = $result['result']['task'];
        });
        $obB24->client->processBatchCalls();
        $date = new \DateTime($task['deadline']);
        $task['deadline'] = $date->format('d.m.Y');
        $text = '[B]одобрена[/B] постановщиком';
        $obB24->client->addBatchCall('imbot.message.add',
                [
                    'BOT_ID' => $command['BOT_ID'], // Идентификатор чат-бота, от которого идет запрос, можно не указывать, если чат-бот всего один
                    'DIALOG_ID' => $source['ID'], // Идентификатор диалога, это либо USER_ID пользователя, либо chatXX - где XX идентификатор чата, передается в событии ONIMBOTMESSAGEADD и ONIMJOINCHAT
                    'URL_PREVIEW' => 'N',
                    'MESSAGE' => 'Завка на перенос крайнего срока задачи ' .
                    '"[URL=https://' . B24ConnectSettings::getParametrByName('b24PortalName') . '/company/personal/user/' . $task['createdBy'] . '/tasks/task/view/' . $task['id'] . '/]' .
                    $task['title'] . '[/URL]" с [B]' . $task['deadline'] . '[/B] на [B]' . $information['deadline'] . '[/B] ' . $text,
        ]);
        $obB24->client->addBatchCall('task.commentitem.add', [$task['id'],
            [
                'POST_MESSAGE' => 'Заявка на перенос крайнего срока задачи от ' . $source['NAME'] . ' ' . $source['LAST_NAME'] .
                ' о смене крайнего срока задачи с [B]' . $task['deadline'] . '[/B] на [B]' . $information['deadline'] . '[/B] ' . $text
            ]
        ]);

        $obB24->client->addBatchCall('imbot.message.update',
                [
                    'BOT_ID' => $command['BOT_ID'], // Идентификатор чат-бота, от которого идет запрос, можно не указывать, если чат-бот всего один
                    'MESSAGE_ID' => $command['MESSAGE_ID'],
                    'MESSAGE' => '[USER=' . $currentUser['ID'] . ']' . $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'] .
                    '[/USER] запрашивает изменение крайнего срока задачи "' .
                    '[URL=https://' . B24ConnectSettings::getParametrByName('b24PortalName') . '/company/personal/user/' . $task['createdBy'] . '/tasks/task/view/' . $task['id'] . '/]' .
                    $task['title'] . '[/URL]" с [B]' . $task['deadline'] . '[/B] на [B]' . $information['deadline'] . '[/B] со следующим комментарием:[B][BR]' . $information['comment'] . '[/B] Вы разрешили перенос срока',
                    'URL_PREVIEW' => 'N',
                    'KEYBOARD' => '',
                ], function ($result) {
            
        });

        $obB24->client->addBatchCall('tasks.task.update',
                [
                    'taskId' => $information['taskId'],
                    'fields' => [
                        'DEADLINE' => $information['deadline']
                    ]
        ]);

        $obB24->client->processBatchCalls();
    }

    private function placementParams($properties, &$source, &$information, &$receiver) {
        try {
            $source = $properties[0];
            $information = ArrayHelper::toArray(json_decode($properties[1]));
            $receiver = $properties[2];
        } catch (Exception $e) {
            return;
        }
    }

}
