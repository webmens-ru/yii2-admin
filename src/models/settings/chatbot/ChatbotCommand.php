<?php

namespace wm\admin\models\settings\chatbot;

use Bitrix24\Im\Im;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_chatbot_command".
 *
 * @property string $bot_code Идентификатор чат-бота
 * @property string $command Текст команды
 * @property string $common Скрытая команда или нет
 * @property string $hidden Доступна ли команда пользователям Экстранет
 * @property string $extranet_support Доступна ли команда пользователям Экстранет
 * @property string $title_ru Описание команды рус
 * @property string $params_ru Праметры рус
 * @property string $title_en Описание команды англ
 * @property string $params_en Праметры англ
 * @property string $event_command_add Ссылка на обработчик для команд
 *
 * @property AdminChatbot $botCode
 */
class ChatbotCommand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_chatbot_command';
    }

    public static $registerErrors = [
        'EVENT_COMMAND_ADD' => 'Ссылка обработчик события невалидная или не указана.',
        'COMMAND_ERROR' => 'Не указан текст команды, на которую должен откликаться чат-бот.',
        'BOT_ID_ERROR' => 'Чат-бот не найден.',
        'APP_ID_ERROR' => 'Чат-бот не принадлежит этому приложению, работать можно только с чат-ботами,
         установленными в рамках приложения.',
        'LANG_ERROR' => 'Не переданы языковые фразы для видимой команды.',
        'WRONG_REQUEST' => 'Что-то пошло не так.'
    ];

    public static $unregisterErrors = [
        'COMMAND_ERROR' => 'Команда не найдена.',
        'APP_ID_ERROR' => 'Чат-бот не принадлежит этому приложению, работать можно только с чат-ботами,
         установленными в рамках приложения.',
        'WRONG_REQUEST' => 'Что-то пошло не так.'
    ];

    public static $updateErrors = [
        'COMMAND_ID_ERROR' => 'Команда не найдена.',
        'APP_ID_ERROR' => 'Чат-бот не принадлежит этому приложению, работать можно только с чат-ботами,
         установленными в рамках приложения.',
        'EVENT_COMMAND_ADD' => 'Ссылка обработчик события невалидная или не указана.',
        'WRONG_REQUEST' => 'Что-то пошло не так.'
    ];

    public static $answerErrors = [
        'COMMAND_ID_ERROR' => 'Команда не найдена.',
        'APP_ID_ERROR' => 'Чат-бот не принадлежит этому приложению, работать можно только с чат-ботами,
         установленными в рамках приложения.',
        'MESSAGE_EMPTY' => 'Не передан текст сообщения.',
        'ATTACH_ERROR' => 'Весь переданный объект вложения не прошел валидацию.',
        'ATTACH_OVERSIZE' => 'Превышен максимально допустимый размер вложения (30 Кб).',
        'KEYBOARD_ERROR' => 'Весь переданный объект клавиатуры не прошел валидацию.',
        'KEYBOARD_OVERSIZE' => 'Превышен максимально допустимый размер клавиатуры (30 Кб).',
        'MENU_ERROR' => 'Весь переданный объект меню не прошел валидацию.',
        'MENU_OVERSIZE' => 'Превышен максимально допустимый размер меню (30 Кб).',
        'WRONG_REQUEST' => 'Что-то пошло не так.'
    ];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'bot_code',
                    'command',
                    'common',
                    'hidden',
                    'extranet_support',
                    'title_ru', 'params_ru',
                    'title_en', 'params_en',
                    'event_command_add'
                ], 'required'],
            [['bot_code'], 'string', 'max' => 64],
            [['command_id'], 'integer'],
            [
                ['command', 'title_ru', 'params_ru', 'title_en', 'params_en', 'event_command_add'],
                'string', 'max' => 255
            ],
            [['common', 'hidden', 'extranet_support'], 'string', 'max' => 1],
            [['bot_code', 'command'], 'unique', 'targetAttribute' => ['bot_code', 'command']],
            [
                ['bot_code'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chatbot::className(),
                'targetAttribute' => ['bot_code' => 'code']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bot_code' => 'Идентификатор чат-бота',
            'command' => 'Текст команды',
            'common' => 'Команда доступна во всех чатах',
            'hidden' => 'Скрытая команда или нет',
            'extranet_support' => 'Доступна ли команда пользователям Экстранет',
            'title_ru' => 'Описание команды рус',
            'params_ru' => 'Праметры рус',
            'title_en' => 'Описание команды англ',
            'params_en' => 'Праметры англ',
            'event_command_add' => 'Ссылка на обработчик для команд',
            'command_id' => 'Ид команды на портале'
        ];
    }

    /**
     * Gets query for [[BotCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBot()
    {
        return $this->hasOne(Chatbot::className(), ['code' => 'bot_code']);
    }

    private function connectBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        return $b24App;
    }

    public function toBitrix24()
    {
        $b24App = $this->connectBitrix24();
        $obB24Im = new Im($b24App);
        $b24 = $obB24Im->client->call('imbot.command.register', [
            'BOT_ID' => $this->bot->bot_id,
            'COMMAND' => $this->command,
            'COMMON' => $this->common,
            'HIDDEN' => $this->hidden,
            'EXTRANET_SUPPORT' => $this->extranet_support,
            'LANG' => array(// Массив переводов, обязательно указывать, как минимум, для RU и EN
                array('LANGUAGE_ID' => 'en', 'TITLE' => $this->title_en, 'PARAMS' => $this->params_en),
                // Язык, описание команды, какие данные после команды нужно вводить.
                array('LANGUAGE_ID' => 'ru', 'TITLE' => $this->title_ru, 'PARAMS' => $this->params_ru)
            ),
            'EVENT_COMMAND_ADD' => Url::toRoute(
                '/handlers/chatbot/' . $this->bot_code . '/' . $this->event_command_add,
                'https'
            ),
        ]);

        if (array_key_exists($b24['result'], self::$registerErrors)) {
            return ['errors' => self::$registerErrors[$b24['result']]];
        }

        $this->command_id = $b24['result'];
        $this->save();
        return $b24;
    }

    public function removeBitrix24()
    {
        $b24App = $this->connectBitrix24();
        $obB24Im = new Im($b24App);
        $b24 = $obB24Im->client->call('imbot.command.unregister', ['COMMAND_ID' => $this->command_id]);
        if (array_key_exists($b24['result'], self::$unregisterErrors)) {
            return ['errors' => self::$unregisterErrors[$b24['result']]];
        }
        $this->command_id = null;
        $this->save();
    }

    public function updateBitrix24()
    {
        $b24App = $this->connectBitrix24();
        $obB24Im = new Im($b24App);
        $b24 = $obB24Im->client->call('imbot.command.update', [
            'COMMAND_ID' => $this->command_id,
            'FIELDS' => array(
                'COMMON' => $this->common,
                'HIDDEN' => $this->hidden,
                'EXTRANET_SUPPORT' => $this->extranet_support,
                'LANG' => array(// Массив переводов, обязательно указывать, как минимум, для RU и EN
                    array('LANGUAGE_ID' => 'en', 'TITLE' => $this->title_en, 'PARAMS' => $this->params_en),
                    // Язык, описание команды, какие данные после команды нужно вводить.
                    array('LANGUAGE_ID' => 'ru', 'TITLE' => $this->title_ru, 'PARAMS' => $this->params_ru)
                ),
                'EVENT_COMMAND_ADD' => Url::toRoute(
                    '/handlers/chatbot/' . $this->bot_code . '/' . $this->event_command_add,
                    'https'
                ),
            ),
        ]);
        if (array_key_exists($b24['result'], self::$updateErrors)) {
            return ['errors' => self::$updateErrors[$b24['result']]];
        }
        return $b24;
    }
}
