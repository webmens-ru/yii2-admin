<?php

namespace wm\admin\models\settings\events;

use Yii;
use wm\b24tools\b24Tools;
use wm\admin\models\B24ConnectSettings;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_events".
 *
 * @property int $id
 * @property string $event_name
 * @property string $handler
 * @property int $auth_type
 * @property int $event_type
 *
 * @property AdminEventsDirectory $eventName
 */
class Events extends \yii\db\ActiveRecord
{
    public static $BASE_URL_HANDLER = '/admin/handlers/event/';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_name', 'handler', 'event_type'], 'required'],
            [['auth_type'], 'integer'],
            [['event_name', 'handler'], 'string', 'max' => 255],
            [['event_type'], 'string', 'max' => 10],
            [
                ['event_name'],
                'exist',
                'skipOnError' => true,
                'targetClass' => EventsDirectory::className(),
                'targetAttribute' => ['event_name' => 'name']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_name' => 'Название события',
            'handler' => 'Ссылка',
            'auth_type' => 'ID пользователя',
            'event_type' => 'Тип события',
        ];
    }

    public function toBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connect(
            B24ConnectSettings::getParametrByName('applicationId'),
            B24ConnectSettings::getParametrByName('applicationSecret'),
            B24ConnectSettings::getParametrByName('b24PortalTable'),
            B24ConnectSettings::getParametrByName('b24PortalName')
        );
        $obB24 = new \Bitrix24\Event\Event($b24App);
        $handler = $this->getUrlHandler();
        $b24 = $obB24->bind(
            $this->event_name,
            $handler,
            $this->auth_type,
            $this->event_type
        );
        return $b24;
    }

    public function removeBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connect(
            B24ConnectSettings::getParametrByName('applicationId'),
            B24ConnectSettings::getParametrByName('applicationSecret'),
            B24ConnectSettings::getParametrByName('b24PortalTable'),
            B24ConnectSettings::getParametrByName('b24PortalName')
        );
        $obB24 = new \Bitrix24\Event\Event($b24App);
        $handler = $this->getUrlHandler();
        $b24 = $obB24->unbind(
            $this->event_name,
            $handler,
            $this->auth_type,
            $this->event_type
        );
    }

    /**
     * Gets query for [[EventName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(EventsDirectory::className(), ['name' => 'event_name']);
    }

    public static function getB24EventsList()
    {
        $component = new b24Tools();
        $b24App = $component->connect(
            B24ConnectSettings::getParametrByName('applicationId'),
            B24ConnectSettings::getParametrByName('applicationSecret'),
            B24ConnectSettings::getParametrByName('b24PortalTable'),
            B24ConnectSettings::getParametrByName('b24PortalName')
        );
        $obB24 = new \Bitrix24\Event\Event($b24App);
        $b24 = $obB24->get();
        return $b24;
    }

    public function getEventName()
    {
        $parent = $this->event_type;
        return $parent ? $parent->name : '';
    }

    private function getUrlHandler()
    {
        $url = '';
        if (strpos($this->handler, '/')) {
            $url = Url::toRoute($this->handler, 'https');
        } else {
            $url = Url::toRoute(self::$BASE_URL_HANDLER . $this->handler, 'https');
        }
        return $url;
    }

    public static function getOffline($eventName)
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App); //
        $fullResult = $obB24->client->call(
            'event.offline.get',
            array(
                    'filter' => [
                        'EVENT_NAME' => $eventName,
                    ]
                )
        );
        return $fullResult;
    }
}
