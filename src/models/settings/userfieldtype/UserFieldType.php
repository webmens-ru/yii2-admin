<?php

namespace wm\admin\models\settings\userfieldtype;

use Bitrix24\B24Object;
use wm\yii\helpers\ArrayHelper;
use Yii;
use wm\b24tools\b24Tools;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_placement".
 *
 * @property int $id
 * @property string $userTypeId
 * @property string $handler
 * @property string $title
 * @property string|null $description
 * @property int|null $optionsHeight
 */
class UserFieldType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_userfieldtype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['userTypeId', 'handler', 'title'], 'required'],
                [['userTypeId', 'handler', 'title', 'description'], 'string', 'max' => 255],
                [['optionsHeight',], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userTypeId' => 'Код типа поля',
            'handler' => 'Handler',
            'title' => 'Надпись на типе',
            'description' => 'Описание типа',
            'optionsHeight' => 'Высота',
        ];
    }

    public static function getB24List()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
        $b24 = $obB24->client->call('userfieldtype.list');
        Yii::warning($b24, '$b24 64');
        return ArrayHelper::getValue($b24, 'result');
    }

    public function toBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
//        $handler = Url::toRoute($this->handler, 'https');
        $params = [
            'USER_TYPE_ID' => $this->userTypeId,
            'HANDLER' => Url::toRoute($this->handler, 'https'),
            'TITLE' => $this->title,
        ];
        if($this->description){
            $params['DESCRIPTION'] = $this->description;
        }
        if($this->optionsHeight){
            $params['OPTIONS']['height'] = $this->optionsHeight;
        }
        $b24 = $obB24->client->call('userfieldtype.add', $params);

        return ArrayHelper::getValue($b24, 'result');
    }

    public function removeBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $b24 = $obB24->client->call('userfieldtype.delete', ['USER_TYPE_ID'=>$this->userTypeId]);
        return ArrayHelper::getValue($b24, 'result');
    }

//    /**
//     * Gets query for [[PlacementName]].
//     *
//     * @return \yii\db\ActiveQuery
//     */
//    public function getPlacement()
//    {
//        return $this->hasOne(PlacementDirectory::class, ['name_id' => 'placement_name']);
//    }
//
//    public function getPlacementName()
//    {
//        $parent = $this->placement;
//        return $parent ? $parent->description : '';
//    }
//
//    private function checkSmartProcess()
//    {
//        if (strpos($this->placement_name, 'CRM_DYNAMIC') !== false) {
//            $this->placement_name = substr($this->placement_name, 0, 12)
//                . $this->entityTypeId
//                . substr($this->placement_name, 12);
//        }
//    }
}
