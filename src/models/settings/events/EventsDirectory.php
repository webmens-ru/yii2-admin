<?php

namespace wm\admin\models\settings\events;

use Yii;

/**
 * This is the model class for table "admin_events_directory".
 *
 * @property string $name
 * @property string $description
 *
 * @property AdminEvents[] $adminEvents
 */
class EventsDirectory extends \yii\db\ActiveRecord {

    public static $CATEGORIES = [
            ['name' => 'Компания'],
            ['name' => 'Контакт'],
            ['name' => 'Лид'],
            ['name' => 'Сделка'],
    ];
    public static $EVENT_TYPES = [
            ['name' => 'Онлайн', 'value' => 'online'],
            ['name' => 'Офлайн', 'value' => 'offline'],
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'admin_events_directory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['name', 'description', 'category_name'], 'required'],
                [['name', 'description'], 'string', 'max' => 255],
                [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
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
    public function getEvents() {
        return $this->hasMany(Events::className(), ['event_name' => 'name']);
    }

//    public function getCategory() {
//        return $this->hasOne(Category::className(), ['category_id' => 'id']);
//    }

    public function getCategoryName() {
        $parent = $this->category_name;
        return $parent ? $parent->name : '';
    }

}
