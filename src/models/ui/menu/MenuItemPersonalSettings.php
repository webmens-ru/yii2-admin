<?php
namespace wm\admin\models\ui\menu;

use Yii;
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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_menu_item_personal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId', 'userId', 'order', 'visible'], 'required'],
            [['itemId', 'userId', 'order', 'visible'], 'integer'],
            [['itemId'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::className(), 'targetAttribute' => ['itemId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasOne(MenuItem::className(), ['id' => 'itemId']);
    }

    /**
     * @param $items
     * @param $userId
     * @return bool
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
     * @param $itemId
     * @param $userId
     * @return array|MenuItemPersonalSettings|\yii\db\ActiveRecord|null
     */
    protected static function getItemPersonalSettings($itemId, $userId)
    {
        $item = self::find()->where(['itemId' => $itemId, 'userId' => $userId])->one();
        if (!$item) {
            $item = new MenuItemPersonalSettings();
            return $item;
        }
        return $item;
    }
}
