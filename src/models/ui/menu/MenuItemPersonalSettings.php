<?php

namespace wm\admin\models\ui\menu;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu_item_personal".
 *
 * @property int $id
 * @property int $itemId
 * @property int $userId
 * @property int $order
 * @property int $visible
 *
 * @property MenuItem $item
 */
class MenuItemPersonalSettings extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_menu_item_personal';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['itemId', 'userId', 'order', 'visible'], 'required'],
            [['itemId', 'userId', 'order'], 'integer'],
            [['visible'], 'boolean'],
            [
                ['itemId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => MenuItem::class,
                'targetAttribute' => ['itemId' => 'id']
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
            'itemId' => 'Item ID',
            'userId' => 'User ID',
            'order' => 'Позиция',
            'visible' => 'Скрытость',
        ];
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(MenuItem::class, ['id' => 'itemId']);
    }


    /**
     * @param mixed[] $items
     * @param int $userId
     * @return true
     * @throws \Exception
     */
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

    /**
     * @param mixed[] $itemId
     * @param int $userId
     * @return MenuItemPersonalSettings
     */
    protected static function getItemPersonalSettings($itemId, $userId)
    {
        $item = self::find()->where(['itemId' => $itemId, 'userId' => $userId])->one();
        if (!$item) {
            $item = new MenuItemPersonalSettings();
        }
        return $item;
    }
}
