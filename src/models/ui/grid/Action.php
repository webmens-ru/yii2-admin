<?php

namespace wm\admin\models\ui\grid;

use wm\admin\models\ui\Entity;

/**
 * This is the model class for table "grid_column".
 * @deprecated
 * @property int $id
 * @property string $entityCode
 * @property string $code
 * @property string $title
 * @property int $visible
 * @property int $order
 * @property string $params
 *
 * @property Entity $entityCode0
 * @property GridColumnPersonal[] $gridColumnPersonals
 */
class Action extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_grid_action';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['entityCode', 'label', 'handler'], 'required'],
            [['entityCode'], 'string', 'max' => 64],
            [['label', 'handler', 'params'], 'string', 'max' => 255],
            [
                ['entityCode'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Entity::class,
                'targetAttribute' => ['entityCode' => 'code']
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
            'entityCode' => 'Entity Code',
            'label' => 'Title',
            'handler' => 'Handler',
            'params' => 'Params',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'label',
            'entityCode',
            'handler',
            'params' => function () {
                return json_decode($this->params);
            }
        ];
    }

    /**
     * Gets query for [[EntityCode0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::class, ['code' => 'entityCode']);
    }
}
