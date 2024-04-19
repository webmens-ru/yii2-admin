<?php

namespace wm\admin\models\ui\filter;

/**
 * This is the model class for table "filter_field_options".
 *
 * @property int $id
 * @property int $fieldId
 * @property string $value
 * @property string $title
 * @property int $order
 *
 * @property FilterField $field
 */
class FilterFieldOptions extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_filter_field_options';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['fieldId', 'value', 'title', 'order'], 'required'],
            [['fieldId', 'order'], 'integer'],
            [['value', 'title'], 'string', 'max' => 255],
            [
                ['fieldId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => FilterField::class,
                'targetAttribute' => ['fieldId' => 'id']
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fieldId' => 'Field ID',
            'value' => 'Value',
            'title' => 'Title',
            'order' => 'Order',
        ];
    }

    /**
     * Gets query for [[Field]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(FilterField::class, ['id' => 'fieldId']);
    }
}
