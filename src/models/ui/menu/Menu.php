<?php

namespace wm\admin\models\ui\menu;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name
 *
 * @property Menuitem[] $menuitems
 */
class Menu extends \wm\yii\db\ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_menu';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id меню',
            'name' => 'Название меню',
        ];
    }

    /**
     * Gets query for [[Menuitems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::class, ['menuId' => 'id']);
    }
}
