<?php

namespace wm\admin\models\settings\placements;

use Yii;
use wm\b24tools\b24Tools;
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
 * @property null|PlacementDirectory $placement
 */
class Placement extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_placement';
    }

    /**
     * @return mixed[]
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
                    'targetClass' => PlacementDirectory::class,
                    'targetAttribute' => ['placement_name' => 'name_id']
                ],
        ];
    }

    /**
     * @return mixed[]
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

    /**
     * @return mixed[]
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function getB24PlacementsList()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\Placement\Placement($b24App);
        $b24 = $obB24->getLocations();
        return $b24;
    }

    /**
     * @return mixed[]
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function toBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\Placement\Placement($b24App);
        $handler = Url::toRoute($this->handler, 'https');

        $this->checkSmartProcess();

        $b24 = $obB24->bind(
            $this->placement_name,
            $handler,
            $this->title,
            $this->description,
            $this->group_name
        );
        return $b24;
    }

    /**
     * @return void
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function removeBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
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
        return $this->hasOne(PlacementDirectory::class, ['name_id' => 'placement_name']);
    }

    /**
     * @return string
     */
    public function getPlacementName()
    {
        $parent = $this->placement;
        return $parent ? $parent->description : '';
    }

    /**
     * @return void
     */
    private function checkSmartProcess()
    {
        if (strpos($this->placement_name, 'CRM_DYNAMIC') !== false) {
            $this->placement_name = substr($this->placement_name, 0, 12)
                . $this->entityTypeId
                . substr($this->placement_name, 12);
        }
    }
}
