<?php

namespace wm\admin\models\settings\placements;

use Yii;
use wm\b24tools\b24Tools;
use wm\admin\models\B24ConnectSettings;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_placement".
 *
 * @property int $id
 * @property string $placement_name
 * @property string $entityTypeId
 * @property string $handler
 * @property string $title
 * @property string|null $description
 * @property string|null $group_name
 *
 * @property AdminPlacementDirectory $placementName
 */
class Placement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_placement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['placement_name', 'handler', 'title'], 'required'],
                [
                    ['entityTypeId'],
                    'required',
                    'when' => function () {
                        return (strpos($this->placement_name, 'CRM_DYNAMIC') !== false);
                    },
                    'whenClient' => "function (attribute, value) {}"
                ],
                [['placement_name', 'entityTypeId'], 'string', 'max' => 50],
                [['handler', 'title', 'description', 'group_name'], 'string', 'max' => 255],
                [
                    ['placement_name'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => PlacementDirectory::className(),
                    'targetAttribute' => ['placement_name' => 'name_id']
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
            'placement_name' => 'Место встройки',
            'entityTypeId' => 'Entity Type Id',
            'handler' => 'Handler',
            'title' => 'Надпись на встройке',
            'description' => 'Описание встройки',
            'group_name' => 'Имя группу встройки',
        ];
    }

    public static function getB24PlacementsList()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\Placement\Placement($b24App);
        $b24 = $obB24->getLocations();
        return $b24;
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
        $obB24 = new \Bitrix24\Placement\Placement($b24App);
        $handler = Url::toRoute($this->handler, 'https');

        $this->checkSmartProcess();
        Yii::warning($this->placement_name, 'placement_name');

        $b24 = $obB24->bind(
            $this->placement_name,
            $handler,
            $this->title,
            $this->description,
            $this->group_name
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
        $obB24 = new \Bitrix24\Placement\Placement($b24App);
        $handler = Url::toRoute($this->handler, 'https');

        $this->checkSmartProcess();

        $b24 = $obB24->unbind(
            $this->placement_name,
            $handler
        );
    }

    /**
     * Gets query for [[PlacementName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlacement()
    {
        return $this->hasOne(PlacementDirectory::className(), ['name_id' => 'placement_name']);
    }

    public function getPlacementName()
    {
        $parent = $this->placement_name;
        return $parent ? $parent->name : '';
    }

    private function checkSmartProcess()
    {
        if (strpos($this->placement_name, 'CRM_DYNAMIC') !== false) {
            $this->placement_name = substr($this->placement_name, 0, 12) . $this->entityTypeId . substr($this->placement_name, 12);
        }
    }
}
