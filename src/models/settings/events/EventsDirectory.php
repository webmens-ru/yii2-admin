<?php

namespace wm\admin\models\settings\events;

use Yii;

/**
 * This is the model class for table "admin_events_directory".
 *
 * @property string $name
 * @property string $description
 * @property string $category_name
 *
 * @property AdminEvents[] $adminEvents
 */
class EventsDirectory extends \yii\db\ActiveRecord
{
    /**
     * @var mixed[]
     */
    public static $CATEGORIES = [
            ['name' => 'Компания'],
            ['name' => 'Контакт'],
            ['name' => 'Лид'],
            ['name' => 'Сделка'],
    ];
    /**
     * @var mixed[]
     */
    public static $EVENT_TYPES = [
            ['name' => 'Онлайн', 'value' => 'online'],
            ['name' => 'Офлайн', 'value' => 'offline'],
    ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_events_directory';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
                [['name', 'description', 'category_name'], 'required'],
                [['name', 'description'], 'string', 'max' => 255],
                [['name'], 'unique'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя события',
            'description' => 'Описание',
            'category_name' => 'Категория'
        ];
    }

    /**
     * Gets query for [[AdminEvents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::class, ['event_name' => 'name']);
    }

//    public function getCategory() {
//        return $this->hasOne(Category::class(), ['category_id' => 'id']);
//    }
//
//    public function getCategoryName()
//    {
//        $parent = $this->category_name;
//        return $parent ? $parent->name : '';
//    }
}
