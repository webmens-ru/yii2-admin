<?php

namespace wm\admin\models\ui;

use Yii;

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
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 32],
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
        return $this->hasMany(FilterField::className(), ['entityCode' => 'code']);
    }

    /**
     * Gets query for [[Filters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(Filter::className(), ['entityCode' => 'code']);
    }
}
