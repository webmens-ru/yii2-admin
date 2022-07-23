<?php

namespace wm\admin\models\ui\filter;

use Yii;
use wm\admin\models\ui\Entity;

/**
 * This is the model class for table "filter_field".
 *
 * @property int $id
 * @property string $entityCode
 * @property int $typeId
 * @property string $title
 * @property int $order
 * @property int $params
 *
 * @property Entity $entityCode0
 * @property FilterFieldOptions[] $filterFieldOptions
 * @property FilterFieldSetting[] $filterFieldSettings
 * @property FilterFieldType $type
 */
class FilterField extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_filter_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entityCode', 'typeId', 'title', 'order', 'code'], 'required'],
            [['typeId', 'order'], 'integer'],
            [['entityCode', 'code'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 255],
            [['params'], 'safe'],
            [['entityCode'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entityCode' => 'code']],
            [['typeId'], 'exist', 'skipOnError' => true, 'targetClass' => FilterFieldType::className(), 'targetAttribute' => ['typeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasOne(Entity::className(), ['code' => 'entityCode']);
    }

    /**
     * Gets query for [[FilterFieldOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterFieldOptions()
    {
        return $this->hasMany(FilterFieldOptions::className(), ['fieldId' => 'id']);
    }

    /**
     * Gets query for [[FilterFieldSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterFieldSettings()
    {
        return $this->hasMany(FilterFieldSetting::className(), ['filterFieldId' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FilterFieldType::className(), ['id' => 'typeId']);
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
                $res = json_decode($this->params);
                return $res;
            },
        ];
    }
}
