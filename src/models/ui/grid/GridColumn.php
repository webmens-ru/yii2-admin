<?php

namespace wm\admin\models\ui\grid;

use Yii;
use wm\admin\models\ui\Entity;

/**
 * This is the model class for table "grid_column".
 *
 * @property int $id
 * @property string $entityCode
 * @property string $code
 * @property string $title
 * @property int $visible
 * @property int $order
 *
 * @property Entity $entityCode0
 * @property GridColumnPersonal[] $gridColumnPersonals
 */
class GridColumn extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_grid_column';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entityCode', 'code', 'title', 'visible', 'order', 'width', ], 'required'],
            [['visible', 'order', 'width'], 'integer'],
            [['entityCode', 'code'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 255],
            [['entityCode'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::className(), 'targetAttribute' => ['entityCode' => 'code']],
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
            'code' => 'Code',
            'title' => 'Title',
            'visible' => 'Visible',
            'order' => 'Order',
            'width' => 'Ширина',
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
     * Gets query for [[GridColumnPersonals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGridColumnPersonals()
    {
        return $this->hasMany(GridColumnPersonal::className(), ['columnId' => 'id']);
    }
    
    public static function getColumns($entityCode, $userId) {
        Yii::warning($entityCode, '$entityCode');
        Yii::warning($userId, '$userId');
        if (!Entity::find()->where(['code' => $entityCode])->one()) {
            Yii::error('$entityCode не содержится в Базе данных');
            return [];
        }
        $models = self::find()->where(['entityCode' => $entityCode])->all();
        $res = [];
        foreach ($models as $value) {
            $settings = $value->getGridColumnPersonals()->where(['userId' => $userId])->one();
            if ($settings) {
                $value->order = $settings->order;
                $value->visible = $settings->visible;
                $value->width = $settings->width;
            }
            $res[] = $value;
        }
        return $res;
    }
}
