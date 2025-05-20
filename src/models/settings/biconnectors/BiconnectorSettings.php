<?php

namespace wm\admin\models\settings\biconnectors;

use Yii;

/**
 * This is the model class for table "admin_biconnector_settings".
 *
 * @property int $biconnectorId
 * @property string $name
 * @property string $type
 * @property string $code
 *
 * @property Biconnector $biconnector
 * @property BiconnectorSettingsType $settingsType
 */
class BiconnectorSettings extends \yii\db\ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_biconnector_settings';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['biconnectorId', 'name', 'type', 'code'], 'required'],
            [['biconnectorId'], 'integer'],
            [['name', 'type', 'code'], 'string', 'max' => 255],
            [['biconnectorId', 'code'], 'unique', 'targetAttribute' => ['biconnectorId', 'code']],
            [['biconnectorId'], 'exist', 'skipOnError' => true, 'targetClass' => Biconnector::class, 'targetAttribute' => ['biconnectorId' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => BiconnectorSettingsType::class, 'targetAttribute' => ['type' => 'code']],
        ];
    }


    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'biconnectorId' => 'Biconnector ID',
            'name' => 'Название',
            'type' => 'Тип',
            'code' => 'Code',
        ];
    }

    /**
     * Gets query for [[Biconnector]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBiconnector()
    {
        return $this->hasOne(Biconnector::class, ['id' => 'biconnectorId']);
    }

    /**
     * Gets query for [[SettingsType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSettingsType()
    {
        return $this->hasOne(BiconnectorSettingsType::class, ['code' => 'type']);
    }
}
