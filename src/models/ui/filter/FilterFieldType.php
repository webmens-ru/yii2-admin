<?php

namespace wm\admin\models\ui\filter;

use Yii;

/**
 * This is the model class for table "filter_field_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property FilterField[] $filterFields
 */
class FilterFieldType extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_filter_field_type';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[FilterFields]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterFields()
    {
        return $this->hasMany(FilterField::class, ['typeId' => 'id']);
    }
}
