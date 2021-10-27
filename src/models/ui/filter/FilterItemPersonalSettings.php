<?php

namespace wm\admin\models\ui\filter;

use Yii;
use yii\helpers\ArrayHelper;

class FilterItemPersonalSettings extends \wm\yii\db\ActiveRecord 
{

    public static function tableName() {
        return 'admin_filter_item_personal_settings';
    }

    public function rules() {
        return [
            [['itemId', 'userId', 'order', 'visible', 'value'], 'required'],
            [['itemId', 'userId', 'order', 'visible'], 'integer'],
            [['value'], 'string'],
            [['itemId'], 'exist', 'skipOnError' => true, 'targetClass' => FiltersItem::className(), 'targetAttribute' => ['itemId' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'itemId' => 'Item ID',
            'userId' => 'User ID',
            'order' => 'Позиция',
            'visible' => 'Скрытость',
            'value' => 'Значение',
        ];
    }

    public function getItem() {
        return $this->hasOne(FilterItem::className(), ['id' => 'itemId']);
    }
    
    
     public static function saveItems($items, $userId)
    {
        foreach ($items as $item) {
            $itemId = ArrayHelper::getValue($item, 'id');
            $model = self::getItemPersonalSettings($itemId, $userId);
            $model->itemId = $itemId;
            $model->order = ArrayHelper::getValue($item, 'order');
            $model->userId = $userId;
            $model->visible = ArrayHelper::getValue($item, 'visible');
            $model->save();
        }


        return true;
    }
    
     protected static function getItemPersonalSettings($itemId, $userId)
    {
        $item = self::find()->where(['itemId' => $itemId, 'userId' => $userId])->one();
        if (!$item) {
            $item = new FilterItemPersonalSettings();
            return $item;
        }
        return $item;
    }

}
