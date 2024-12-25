<?php

namespace wm\admin\models;

use Yii;

/**
 * This is the model class for table "admin_settings".
 *
 * @property string $name_id
 * @property string $value
 * @property string $name
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_settings';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name_id', 'value', 'name'], 'required'],
            [['name_id', 'value', 'name'], 'string', 'max' => 255],
            [['name_id'], 'unique'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'name_id' => 'Системное имя',
            'value' => 'Значение',
            'name' => 'Описание',
        ];
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getParametrByName($name)
    {
        return self::find()->where(['name_id' => $name])->one()->value;
    }
}
