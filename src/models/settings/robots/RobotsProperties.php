<?php

namespace wm\admin\models\settings\robots;

/**
 * This is the model class for table "admin_robots_properties".
 *
 * @property string $robot_code
 * @property int|null $is_in
 * @property string $system_name
 * @property string $name
 * @property string $description
 * @property int $type_id
 * @property int $required
 * @property int $multiple
 * @property string|null $default
 * @property int $sort
 *
 * @property RobotsOptions[] $options
 * @property Robots $robotCode
 * @property RobotsTypes $type
 * @property Robots $robot
 */
class RobotsProperties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_robots_properties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['robot_code', 'system_name', 'name', 'description', 'type_id', 'required', 'multiple', 'sort'],
                'required'
            ],
            [['is_in', 'type_id', 'required', 'multiple', 'sort'], 'integer'],
            [['robot_code', 'system_name', 'name', 'description', 'default'], 'string', 'max' => 255],
            [['robot_code', 'system_name'], 'unique', 'targetAttribute' => ['robot_code', 'system_name']],
            [
                ['robot_code'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Robots::class,
                'targetAttribute' => ['robot_code' => 'code']
            ],
            [
                ['type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => RobotsTypes::class,
                'targetAttribute' => ['type_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'robot_code' => 'ID робота',
            'is_in' => 'Входящий',
            'system_name' => 'Сис. название',
            'name' => 'Название',
            'description' => 'Описание',
            'type_id' => 'Тип данных',
            'required' => 'Обязательность',
            'multiple' => 'Множественность',
            'default' => 'По умолчанию',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(
            RobotsOptions::class,
            [
                'robot_code' => 'robot_code',
                'property_name' => 'system_name'
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRobot()
    {
        return $this->hasOne(Robots::class, ['code' => 'robot_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(RobotsTypes::class, ['id' => 'type_id']);
    }

    public function getRobotName()
    {
        $parent = $this->robot;
        return $parent ? $parent->name : '';
    }

    public function getTypeName()
    {
        $parent = $this->type;
        return $parent ? $parent->name : '';
    }

    public static function getPropertiesTypeSelectStatic()
    {
        $typeId = RobotsOptions::find()->where(['name' => 'select_static'])->one()->id;
        $properties = self::find()->where(['type_id' => $typeId])->all();
        return $properties;
    }
}
