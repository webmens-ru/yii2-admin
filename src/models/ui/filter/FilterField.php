<?php

namespace wm\admin\models\ui\filter;

use wm\admin\models\ui\Entity;
use yii\helpers\Json;

/**
 * This is the model class for table "filter_field".
 *
 * @property int $id
 * @property string $entityCode
 * @property int $typeId
 * @property string $title
 * @property int $order
 * @property string $params
 * @property string $filtrationType
 * @property string $options
 * @property string $code
 *
 * @property Entity $entityCode0
 * @property FilterFieldOptions[] $filterFieldOptions
 * @property FilterFieldSetting[] $filterFieldSettings
 * @property FilterFieldType $type
 */
class FilterField extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_filter_field';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['entityCode', 'typeId', 'title', 'order', 'code'], 'required'],
            [['typeId', 'order'], 'integer'],
            [['entityCode'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 255],
            [['params'], 'safe'],
            [
                ['entityCode'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Entity::class,
                'targetAttribute' => ['entityCode' => 'code']
            ],
            [
                ['typeId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => FilterFieldType::class,
                'targetAttribute' => ['typeId' => 'id']
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
            'typeId' => 'Type ID',
            'title' => 'Title',
            'order' => 'Order',
            'code' => 'Code',
            'params' => 'Params',
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

    /**
     * Gets query for [[FilterFieldOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterFieldOptions()
    {
        return $this->hasMany(FilterFieldOptions::class, ['fieldId' => 'id']);
    }

    /**
     * Gets query for [[FilterFieldSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterFieldSettings()
    {
        return $this->hasMany(FilterFieldSetting::class, ['filterFieldId' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FilterFieldType::class, ['id' => 'typeId']);
    }

    public function fields()
    {
        return [
            'id',
            'entityCode',
            'typeId',
            'title',
            'order',
            'type',
            'filterFieldOptions',
            'code',
            'params' => function () {
                $res = Json::decode($this->params);
                return $res;
            },
            'options',
        ];
    }
}
