<?php

namespace wm\admin\models;

use Yii;

/**
 * This is the model class for table "windows_settings".
 *
 * @property string $ID
 * @property string $CATEGORY_ID
 * @property string $COLOR
 * @property string $ENTITY_ID
 * @property string $NAME
 * @property string $NAME_INIT
 * @property string $SEMANTICS
 * @property string $SORT
 * @property string $STATUS_ID
 * @property string $SYSTEM
 */
class CrmStatus extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_crm_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'string', 'max' => 32],
            [['ID'], 'unique'],
            [[
                'CATEGORY_ID',
                'COLOR',
                'ENTITY_ID',
                'NAME',
                'NAME_INIT',
                'SEMANTICS',
                'SORT',
                'STATUS_ID',
                'SYSTEM'
            ], 'string', 'max' => 255]
        ];
    }
}
