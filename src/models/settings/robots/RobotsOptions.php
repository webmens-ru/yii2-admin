<?php

namespace wm\admin\models\settings\robots;

/**
 * This is the model class for table "admin_robots_options".
 *
 * @property int $id
 * @property int $property_id
 * @property string $property_name
 * @property string $robot_code
 * @property string $value
 * @property string $name
 * @property int $sort
 *
 * @property AdminRobotsProperties $robotCode
 */
class RobotsOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_robots_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_name', 'robot_code', 'value', 'name'], 'required'],
            [['sort'], 'integer'],
            [['property_name', 'robot_code', 'value', 'name'], 'string', 'max' => 255],
            [
                ['property_name', 'robot_code', 'value'],
                'unique',
                'targetAttribute' => ['property_name', 'robot_code', 'value']
            ],
            [
                ['robot_code', 'property_name'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RobotsProperties::className(),
                'targetAttribute' => ['robot_code' => 'robot_code', 'property_name' => 'system_name']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'property_name' => 'Название параметра',
            'robot_code' => 'ID робота',
            'value' => 'Значение',
            'name' => 'Имя',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this
            ->hasOne(
                RobotsProperties::className(),
                ['robot_code' => 'robot_code', 'system_name' => 'property_name']
            );
    }

    /*public function getProperty() {
        return $this->hasOne(RobotsProperties::className(), ['id' => 'property_id']);
    }

    public function getPropertyName() {
        $parent = $this->property;
        return $parent ? $parent->name : '';
    }*/
}
