<?php

namespace wm\admin\models\settings\placements;

use Yii;

/**
 * This is the model class for table "admin_placement_directory".
 *
 * @property string $name_id
 * @property string $description
 *
 * @property AdminPlacement[] $adminPlacements
 */
class PlacementDirectory extends \yii\db\ActiveRecord
{
    public static $CATEGORIES = [
            ['name' => 'Компания'],
            ['name' => 'Контакт'],
            ['name' => 'Лид'],
            ['name' => 'Сделка'],
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_placement_directory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['name_id', 'description', 'category_name'], 'required'],
                [['name_id'], 'string', 'max' => 50],
                [['category_name'], 'string', 'max' => 100],
                [['description'], 'string', 'max' => 255],
                [['name_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name_id' => 'Идентификатор места встройки',
            'description' => 'Описание',
            'category_name' => 'Категория',
        ];
    }

    /**
     * Gets query for [[AdminPlacements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlacements()
    {
        return $this->hasMany(Placement::class, ['placement_name' => 'name_id']);
    }
}
