<?php

namespace wm\admin\models\ui;

use Yii;
use wm\admin\models\ui\filter\FilterField;
use wm\admin\models\ui\filter\Filter;

/**
 * This is the model class for table "entity".
 *
 * @property string $code
 * @property string $name
 *
 * @property FilterField[] $filterFields
 * @property Filter[] $filters
 */
class Entity extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[FilterFields]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterFields()
    {
        return $this->hasMany(FilterField::class, ['entityCode' => 'code']);
    }

    /**
     * Gets query for [[Filters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(Filter::class, ['entityCode' => 'code']);
    }
}
