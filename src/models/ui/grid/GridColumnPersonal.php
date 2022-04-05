<?php

namespace wm\admin\models\ui\grid;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "grid_column_personal".
 *
 * @property int $id
 * @property int $columnId
 * @property int $userId
 * @property int $order
 * @property int $visible
 *
 * @property GridColumn $column
 */
class GridColumnPersonal extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_grid_column_personal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['columnId', 'userId', 'order', 'visible'], 'required'],
            [['columnId', 'userId', 'order', 'visible'], 'integer'],
            [['columnId'], 'exist', 'skipOnError' => true, 'targetClass' => GridColumn::className(), 'targetAttribute' => ['columnId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'columnId' => 'Column ID',
            'userId' => 'User ID',
            'order' => 'Order',
            'visible' => 'Visible',
            'width' => 'Ширина',
        ];
    }

    /**
     * Gets query for [[Column]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColumn()
    {
        return $this->hasOne(GridColumn::className(), ['id' => 'columnId']);
    }

    /**
     * @param $columns
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public static function saveColumns($columns, $userId)
    {
        foreach ($columns as $column) {
            $columnId = ArrayHelper::getValue($column, 'id');
            $model = self::getColumnPersonalSettings($columnId, $userId);
            $model->columnId = $columnId;
            $model->order = ArrayHelper::getValue($column, 'order');
            $model->userId = $userId;
            $model->visible = ArrayHelper::getValue($column, 'visible');
            $model->width = ArrayHelper::getValue($column, 'width');
            $model->save();
        }


        return true;
    }

    /**
     * @param $columnId
     * @param $userId
     * @return array|GridColumnPersonal|\yii\db\ActiveRecord|null
     */
    protected static function getColumnPersonalSettings($columnId, $userId)
    {
        $column = self::find()->where(['columnId' => $columnId, 'userId' => $userId])->one();
        if (!$column) {
            $column = new GridColumnPersonal();           
        }
        return $column;
    }
}
