<?php

namespace wm\admin\models\ui\filter;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "filter_field_setting".
 *
 * @property int $id
 * @property int $filterId
 * @property int $filterFieldId
 * @property string $value
 * @property int $order
 *
 * @property Filter $filter
 * @property FilterField $filterField
 */
class FilterFieldSetting extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_filter_field_setting';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['filterId', 'filterFieldId', 'order'], 'required'],
            [['filterId', 'filterFieldId', 'order'], 'integer'],
            //[['filterId', 'filterFieldId'], 'unique'],
            [['value'], 'safe'],
            [
                ['filterId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Filter::className(),
                'targetAttribute' => ['filterId' => 'id']
            ],
            [
                ['filterFieldId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => FilterField::className(),
                'targetAttribute' => ['filterFieldId' => 'id']
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filterId' => 'Filter ID',
            'filterFieldId' => 'Filter Field ID',
            'value' => 'Value',
            'order' => 'Order',
        ];
    }

    /**
     * Gets query for [[Filter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilter()
    {
        return $this->hasOne(Filter::className(), ['id' => 'filterId']);
    }

    /**
     * Gets query for [[FilterField]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilterField()
    {
        return $this->hasOne(FilterField::className(), ['id' => 'filterFieldId']);
    }

    /**
     * @param $oldFilterId
     * @param $newFilterId
     */
    public static function copyField($oldFilterId, $newFilterId)
    {
        $models = self::find()->where(['filterId' => $oldFilterId])->all();
        foreach ($models as $value) {
            $model = new FilterFieldSetting();
            $model->filterId = $newFilterId;
            $model->filterFieldId = $value->filterFieldId;
            $model->value = $value->value;
            $model->order = $value->order;
            $model->save();
            if ($model->errors) {
                Yii::error($model->errors, 'copyField FilterFieldSetting');
            }
        }
    }

    /**
     * @param $params
     * @param $filter
     * @return bool
     */
    public static function add($params, $filter)
    {
        foreach ($params as $value) {
            $value['filterId'] = $filter->id;
            $value['value'] = json_encode($value['value']);

            $model = new FilterFieldSetting();
            $model->load($value);
            $model->save();
            if ($model->errors) {
                Yii::error($model->errors, 'FilterFieldSetting add');
            }
        }
        return true;
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public static function editOrder($params)
    {
        foreach ($params as $param) {
            $model = self::find()->where(['id' => $param['id'],])->one();
            $model->order = ArrayHelper::getValue($param, 'order');
            $model->save();
            if ($model->errors) {
                Yii::error($model->errors, 'FilterFieldSetting editOrder');
            }
        }
    }
}
