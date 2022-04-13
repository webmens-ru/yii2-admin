<?php

namespace wm\admin\models\ui\form;

/**
 * This is the model class for table "admin_form_fields".
 *
 * @property integer $id
 *
 * @property string $mode
 * @property string $title
 *
 * @property boolean $canToggleMode
 *
 * @property Fields[] $fields
 */
class Forms extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_forms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'mode',
                'title',
                'canToggleMode'
            ], 'required'],
            [['id'], 'integer'],
            [['id'], 'unique'],
            [['mode',], 'string', 'max' => 5],
            [['title',], 'string', 'max' => 255],
            [['canToggleMode'], 'boolean'],
        ];
    }

    /**
     * Gets query for [[Fields]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Fields::class, ['formId' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'mode',
            'title',
            'canToggleMode',
            'action',
            'params',
            'buttons',
            'fields'
        ];
    }

}