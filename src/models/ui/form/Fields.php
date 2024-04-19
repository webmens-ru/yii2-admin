<?php

namespace wm\admin\models\ui\form;

use wm\admin\models\ui\form\Form;

///**
// * This is the model class for table "admin_form_fields".
// *
// * @property integer $id
// * @property int formId
// * @property string $type
// * @property string $name
// * @property string $label
// *
// * @property Form[] $form
// */
class Fields extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_form_fields';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [[
                'name',
                'type',
                'label',
                'formId'
            ], 'required'],
            [['formId'], 'integer'],
            [['type',], 'string', 'max' => 20],
            [['name', 'label'], 'string', 'max' => 255],
            [['fieldParams'], 'safe']
        ];
    }

    /**
     * Gets query for [[Form]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::class, ['id' => 'formId']);
    }
}
