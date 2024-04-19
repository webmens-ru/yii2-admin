<?php

namespace wm\admin\models\settings\robots;

use Yii;

/**
 * This is the model class for table "admin_robots_types".
 *
 * @property int $id
 * @property string $name
 * @property int $is_static
 * @property bool $is_options
 *
 * @property AdminRobotsProperties[] $adminRobotsProperties
 */
class RobotsTypes extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_robots_types';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'is_options'], 'required'],
            [['name'], 'unique'],
            [['is_static', 'is_options'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'is_static' => 'Is Static',
            'is_options' => 'Is Options',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(RobotsProperties::class, ['type_id' => 'id']);
    }
}
