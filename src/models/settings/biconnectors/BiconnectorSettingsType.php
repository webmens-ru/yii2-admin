<?php

namespace wm\admin\models\settings\biconnectors;

use Yii;

/**
 * This is the model class for table "admin_biconnector_settings_type".
 *
 * @property string $code
 * @property string $title
 *
 * @property BiconnectorSettings[] $adminBiconnectorSettings
 */
class BiconnectorSettingsType extends \yii\db\ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_biconnector_settings_type';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['code', 'title'], 'required'],
            [['code', 'title'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }


    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[BiconnectorSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBiconnectorSettings()
    {
        return $this->hasMany(BiconnectorSettings::class, ['type' => 'code']);
    }
}
