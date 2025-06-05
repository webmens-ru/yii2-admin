<?php

namespace wm\admin\models\settings\chatbot;

use Yii;
use Bitrix24\Im\Im;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_chatbot".
 *
 * @property string $code Код
 * @property string $type_id Тип
 * @property int|null $openline Является открытой линией
 * @property string $p_name Имя
 * @property string $p_last_name Фамилия
 * @property string $p_color_name Цвет
 * @property string $p_email email
 * @property string|null $p_personal_birthday День рождения
 * @property string|null $p_work_position Должность
 * @property string|null $p_personal_www Персональный сайт
 * @property int|null $p_personal_gender Пол
 * @property string|null $p_personal_photo_url Фото
 * @property string|null $event_handler
 * @property string|null $event_massege_add
 * @property string|null $event_massege_update
 * @property string|null $event_massege_delete
 * @property string|null $event_welcome_massege
 * @property string|null $event_bot_delete
 * @property int|null $bot_id
 *
 * @property AdminChatbotTypeDirectory $type
 * @property AdminChatbotColorDirectory $pColorName
 * @property AdminChatbotCommand[] $adminChatbotCommands
 */
class Chatbot extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_chatbot';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['code', 'type_id', 'p_name', 'p_last_name', 'p_color_name', 'p_email'], 'required'],
            [['p_personal_gender', 'bot_id'], 'integer'],
            [['p_personal_birthday'], 'safe'],
            [
                [
                    'p_name',
                    'p_last_name',
                    'p_email',
                    'p_work_position',
                    'p_personal_www',
                    'p_personal_photo_url',
                    'event_handler',
                    'event_massege_add',
                    'event_massege_update',
                    'event_massege_delete',
                    'event_welcome_massege',
                    'event_bot_delete'
                ],
                'string', 'max' => 255
            ],
            [['openline', 'type_id'], 'string', 'max' => 1],
            [['p_color_name'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 64],
            [['code'], 'unique'],
            [
                ['type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ChatbotTypeDirectory::class,
                'targetAttribute' => ['type_id' => 'name']
            ],
            [
                ['p_color_name'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ChatbotColorDirectory::class,
                'targetAttribute' => ['p_color_name' => 'name']
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'type_id' => 'Тип',
            'openline' => 'Является открытой линией',
            'p_name' => 'Имя',
            'p_last_name' => 'Фамилия',
            'p_color_name' => 'Цвет',
            'p_email' => 'email',
            'p_personal_birthday' => 'День рождения',
            'p_work_position' => 'Должность',
            'p_personal_www' => 'Персональный сайт',
            'p_personal_gender' => 'Пол',
            'p_personal_photo_url' => 'Фото',
            'event_handler' => 'Event Handler',
            'event_massege_add' => 'Event Massege Add',
            'event_massege_update' => 'Event Massege Update',
            'event_massege_delete' => 'Event Massege Delete',
            'event_welcome_massege' => 'Event Welcome Massege',
            'event_bot_delete' => 'Event Bot Delete',
            'bot_id' => 'Идентификатар установленного бота',
        ];
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ChatbotTypeDirectory::class, ['name' => 'type_id']);
    }

    /**
     * Gets query for [[PColorName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPColorName()
    {
        return $this->hasOne(ChatbotColorDirectory::class, ['name' => 'p_color_name']);
    }

    /**
     * @return string[]
     */
    public static function getOpenLineList()
    {
        return ['Y' => 'Да'];
    }

    /**
     * @return mixed[]
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function toBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24Im = new Im($b24App);
        $botParams = [
            'CODE' => $this->code,
            'TYPE' => $this->type_id,
            'OPENLINE' => 'N',
            'PROPERTIES' => [
                'NAME' => $this->p_name,
                'COLOR' => $this->p_color_name,
                ]
        ];

        if ($this->event_handler) {
            $botParams['EVENT_HANDLER'] = Url::toRoute($this->event_handler, 'https');
        } else {
            $botParams['EVENT_MESSAGE_ADD'] = Url::toRoute($this->event_massege_add ?: '', 'https');
            $botParams['EVENT_WELCOME_MESSAGE'] = Url::toRoute($this->event_welcome_massege ?: '', 'https');
            $botParams['EVENT_BOT_DELETE'] = Url::toRoute($this->event_bot_delete ?: '', 'https');
            $botParams['EVENT_MESSAGE_UPDATE'] = Url::toRoute($this->event_massege_update ?: '', 'https');
            $botParams['EVENT_MESSAGE_DELETE'] = Url::toRoute($this->event_massege_delete ?: '', 'https');
        }


        $b24 = $obB24Im->client->call('imbot.register', $botParams);
        $this->bot_id = $b24['result'];
        $this->save();
        return $b24;
    }

    /**
     * @return mixed[]
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function updateBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24Im = new Im($b24App);
        $botParams = [
            'CODE' => $this->code,
            'TYPE' => $this->type_id,
            'OPENLINE' => 'N',
            'PROPERTIES' => [
                'NAME' => $this->p_name,
                'COLOR' => $this->p_color_name,
                ]
        ];

        if ($this->event_handler) {
            $botParams['EVENT_HANDLER'] = Url::toRoute($this->event_handler, 'https');
        } else {
            $botParams['EVENT_MESSAGE_ADD'] = Url::toRoute($this->event_massege_add ?: '', 'https');
            $botParams['EVENT_WELCOME_MESSAGE'] = Url::toRoute($this->event_welcome_massege ?: '', 'https');
            $botParams['EVENT_BOT_DELETE'] = Url::toRoute($this->event_bot_delete ?: '', 'https');
            $botParams['EVENT_MESSAGE_UPDATE'] = Url::toRoute($this->event_massege_update ?: '', 'https');
            $botParams['EVENT_MESSAGE_DELETE'] = Url::toRoute($this->event_massege_delete ?: '', 'https');
        }


        $b24 = $obB24Im->client->call('imbot.update', $botParams);
        return $b24;
    }

    /**
     * @return mixed[]
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function getB24List()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24Im = new Im($b24App);
        $b24 = $obB24Im->client->call('imbot.bot.list', []);
        return $b24;
    }

    /**
     * @return void
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function removeBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
//        $obB24 = new \Bitrix24\Bizproc\Robot($b24App);
//        $b24 = $obB24->delete($this->code);

        $obB24Im = new Im($b24App);
        $b24 = $obB24Im->client->call('imbot.unregister', ['BOT_ID' => $this->bot_id]);
        $this->bot_id = null;
        $this->save();
    }

    /**
     * Gets query for [[AdminChatbotCommands]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatbotCommands()
    {
        return $this->hasMany(ChatbotCommand::class, ['bot_code' => 'code']);
    }

    /**
    * Gets query for [[AdminChatbotApps]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getApps()
    {
        return $this->hasMany(App::class, ['bot_code' => 'code']);
    }
}
