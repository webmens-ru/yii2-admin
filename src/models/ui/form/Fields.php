<?php

namespace wm\admin\models\ui\form;

/**
 * This is the model class for table "admin_form_fields".
 *
 * @property integer $id
 * @property integer formId
 * @property string $type
 * @property string $name
 * @property string $label
 */
class Fields extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_form_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'type',
                'name',
                'label',
                'formId'
            ], 'required'],
            [['id' , 'formId'], 'integer'],
            [['id'], 'unique'],
            [['type',], 'string', 'max' => 20],
            [['name', 'label'], 'string', 'max' => 255]
        ];
    }

    public function getForm()
    {
        return $this->hasOne(Forms::class, ['id' => 'formId']);
    }
}