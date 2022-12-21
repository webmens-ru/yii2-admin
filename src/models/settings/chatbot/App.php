<?php

namespace wm\admin\models\settings\chatbot;

use Bitrix24\Im\Im;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_chatbot_app".
 *
 * @property string $bot_code
 * @property string $code
 * @property string $js_method_code
 * @property string $js_param
 * @property string $icon_file
 * @property string $contex_code
 * @property string $extranet_support
 * @property string $iframe_popup
 * @property string $title_ru
 * @property string $title_en
 * @property string $iframe
 * @property int $iframe_height
 * @property int $iframe_width
 * @property string $hash
 * @property string $hidden
 * @property string $livechat_support
 *
 * @property ChatbotAppContexDirectory $contexCode
 * @property ChatbotAppJsMethodDirectory $jsMethodCode
 * @property Chatbot $botCode
 */
class App extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_chatbot_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['js_method_code', 'js_param',],
                'required',
                'when' => function ($model) {
                    return $model->type == 'js';
                },
                'whenClient' => "function (attribute, value) {return $('#app-type').val()=='js';}"
            ],
            [
                ['iframe', 'iframe_height', 'iframe_width', 'hash', 'hidden',],
                'required',
                'when' => function ($model) {
                    return $model->type == 'iframe';
                },
                'whenClient' => "function (attribute, value) {return $('#app-type').val()=='iframe';}"
            ],
//            [
//                ['iframe_height'],
//                'required',
//                'when' => function($model) {
//                    return $model->type == 'iframe';
//                },
//                'whenClient' => "function (attribute, value) {return $('#app-type').val()=='iframe';}"
//            ],
            [
                [
                    'bot_code', 'code',
                    /* 'js_method_code', 'js_param', */
                    'contex_code',
                    'extranet_support',
                    'iframe_popup',
                    'title_ru',
                    'title_en',
                    /* 'iframe', 'iframe_height', 'iframe_width', 'hash', 'hidden', */
                    'livechat_support',
                    'type'
                ],
                'required'
            ],
            [['icon_file'], 'string'],
            [['iframe_height', 'iframe_width', 'app_id'], 'integer'],
            [['bot_code', 'code', 'js_method_code', 'js_param', 'contex_code', 'hash', 'type'], 'string', 'max' => 32],
            [['extranet_support', 'iframe_popup', 'hidden', 'livechat_support'], 'string', 'max' => 1],
            [['title_ru', 'title_en', 'iframe'], 'string', 'max' => 255],
            [['bot_code', 'code'], 'unique', 'targetAttribute' => ['bot_code', 'code']],
            [
                ['contex_code'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AppContexDirectory::class,
                'targetAttribute' => ['contex_code' => 'code']
            ],
            [
                ['js_method_code'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AppJsMethodDirectory::class,
                'targetAttribute' => ['js_method_code' => 'code']
            ],
            [
                ['bot_code'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Chatbot::class,
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
            'bot_code' => 'Bot Code',
            'code' => 'Код приложения для чата',
            'js_method_code' => 'Js Method Code',
            'js_param' => 'Js Param',
            'icon_file' => 'Icon File',
            'contex_code' => 'Contex Code',
            'extranet_support' => 'Доступна ли команда пользователям экстранет',
            'iframe_popup' => 'iframe будет открыт с возможностью перемещения внутри мессенджера, 
            переход между диалогами не будет закрывать такое окно.',
            'title_ru' => 'Русское название',
            'title_en' => 'Ангимйское название',
            'iframe' => 'URL адрес фрейма',
            'iframe_height' => 'Высота фрейма',
            'iframe_width' => 'Ширина фрейма',
            'hash' => 'Hash',
            'hidden' => 'Скрытое приложение или нет',
            'livechat_support' => 'Поддержка онлайн-чата',
            'type' => 'Тип приложения',
        ];
    }

    /**
     * Gets query for [[ContexCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContexCode()
    {
        return $this->hasOne(AppContexDirectory::class, ['code' => 'contex_code']);
    }

    /**
     * Gets query for [[JsMethodCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJsMethodCode()
    {
        return $this->hasOne(AppJsMethodDirectory::class, ['code' => 'js_method_code']);
    }

    /**
     * Gets query for [[BotCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBotCode()
    {
        return $this->hasOne(Chatbot::class, ['code' => 'bot_code']);
    }

    public function toBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24Im = new Im($b24App);
        $appParams = [
            'BOT_ID' => $this->botCode->bot_id,
            'CODE' => $this->code,
            'CONTEXT' => $this->contex_code,
            'EXTRANET_SUPPORT' => $this->extranet_support,
            'LIVECHAT_SUPPORT' => $this->livechat_support,
            'IFRAME_POPUP' => $this->iframe_popup,
            'LANG' => [
                ['LANGUAGE_ID' => 'en', 'TITLE' => $this->title_en, 'DESCRIPTION' => $this->title_en],
                ['LANGUAGE_ID' => 'ru', 'TITLE' => $this->title_ru, 'DESCRIPTION' => $this->title_ru]
            ],
        ];
        if ($this->type == 'js') {
            $appParams['JS_METHOD'] = $this->js_method_code;
            $appParams['JS_PARAM'] = $this->js_param;
            $appParams['ICON_FILE'] = $this->icon_file;
        }
        if ($this->type == 'iframe') {
            $appParams['IFRAME'] = Url::toRoute(
                '/admin/handlers/chatbot/' . $this->bot_code . '/' . $this->iframe,
                'https'
            );
            $appParams['IFRAME_WIDTH'] = $this->iframe_width;
            $appParams['IFRAME_HEIGHT'] = $this->iframe_height;
            $appParams['HASH'] = $this->hash;
            $appParams['HIDDEN'] = $this->hidden;
        }

        $b24 = $obB24Im->client->call('imbot.app.register', $appParams);
        $this->app_id = $b24['result'];
        $this->save();
        return $b24;
    }

    public function updateBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24Im = new Im($b24App);
        $appParams = [];
        if ($this->type == 'js') {
            $appParams['APP_ID'] = $this->app_id;
            $appParams['FIELDS'] = [
                'BOT_ID' => $this->botCode->bot_id,
                'CODE' => $this->code,
                'JS_METHOD' => $this->js_method_code,
                'JS_PARAM' => $this->js_param,
                'ICON_FILE' => $this->icon_file,
                'CONTEXT' => $this->contex_code,
                'EXTRANET_SUPPORT' => $this->extranet_support,
                'LIVECHAT_SUPPORT' => $this->livechat_support,
                'IFRAME_POPUP' => $this->iframe_popup,
                'LANG' => [
                    ['LANGUAGE_ID' => 'en', 'TITLE' => $this->title_en, 'DESCRIPTION' => $this->title_en],
                    ['LANGUAGE_ID' => 'ru', 'TITLE' => $this->title_ru, 'DESCRIPTION' => $this->title_ru]
                ]
            ];
        }
        if ($this->type == 'iframe') {
            $appParams['APP_ID'] = $this->app_id;
            $appParams['FIELDS'] = [
                'BOT_ID' => $this->botCode->bot_id,
                'CODE' => $this->code,
                'ICON_FILE' => $this->icon_file,
                'CONTEXT' => $this->contex_code,
                'EXTRANET_SUPPORT' => $this->extranet_support,
                'LIVECHAT_SUPPORT' => $this->livechat_support,
                'IFRAME_POPUP' => $this->iframe_popup,
                'IFRAME' => Url::toRoute(
                    '/admin/handlers/chatbot/' . $this->bot_code . '/' . $this->iframe,
                    'https'
                ),
                'IFRAME_WIDTH' => $this->iframe_width,
                'IFRAME_HEIGHT' => $this->iframe_height,
                'HASH' => $this->hash,
                'HIDDEN' => $this->hidden,
                'LANG' => [
                    ['LANGUAGE_ID' => 'en', 'TITLE' => $this->title_en, 'DESCRIPTION' => $this->title_en],
                    ['LANGUAGE_ID' => 'ru', 'TITLE' => $this->title_ru, 'DESCRIPTION' => $this->title_ru]
                ]
            ];
        }

        $b24 = $obB24Im->client->call('imbot.app.update', $appParams);
        return $b24;
    }
}
