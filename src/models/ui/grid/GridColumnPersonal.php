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
 * @property int $width
 * @property int $frozen
 *
 * @property GridColumn $column
 */
class GridColumnPersonal extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_grid_column_personal';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['columnId', 'userId', 'order', 'visible', 'width'], 'required'],
            [['columnId', 'userId', 'order', 'width'], 'integer'],
            [['visible', 'frozen'], 'boolean'],
            [
                ['columnId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => GridColumn::class,
                'targetAttribute' => ['columnId' => 'id']
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
            'columnId' => 'Column ID',
            'userId' => 'User ID',
            'order' => 'Order',
            'visible' => 'Visible',
            'width' => 'Ширина',
            'frozen' => 'Заморозка'
        ];
    }

    /**
     * @return mixed[]
     */
    public function fields()
    {
        return [
            'id',
            'columnId',
            'userId',
            'visible' => function () {
                return self::getBooleanValue($this->visible);
            },
            'frozen' => function () {
                return self::getBooleanValue($this->frozen);
            }
        ];
    }

    /**
     * Gets query for [[Column]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColumn()
    {
        return $this->hasOne(GridColumn::class, ['id' => 'columnId']);
    }

    /**
     * @param mixed[] $columns
     * @param int $userId
     * @return bool
     * @throws \Exception
     */
    public static function saveColumns($columns, $userId)
    {
        foreach ($columns as $column) {
            $columnId = ArrayHelper::getValue($column, 'id');
            $model = self::getColumnPersonalSettings($columnId, $userId);
            $model->load($column, '');
            $model->columnId = $columnId;
            $model->userId = $userId;
            $model->save();
            if ($model->errors) {
                Yii::error(
                    [
                    'model' => $model->errors,
                    'column' => $column,
                    'userId' => $userId
                    ],
                    'GridColumnPersonal->saveColumns($columns, $userId)'
                );
            }
        }
        return true;
    }

    /**
     * @param int $columnId
     * @param int $userId
     * @return GridColumnPersonal
     */
    protected static function getColumnPersonalSettings($columnId, $userId)
    {
        $column = self::find()->where(['columnId' => $columnId, 'userId' => $userId])->one();
        if (!$column) {
            $column = new GridColumnPersonal();
        }
        return $column;
    }

    /**
     * @param string $entity
     * @param string $columnTitle
     * @param int $frozen
     * @param int $userId
     * @return GridColumnPersonal
     */
    public static function setFrozen($entity, $columnTitle, $frozen, $userId)
    {
        $model = GridColumn::find()->where(['entity' => $entity, 'title' => $columnTitle])->one();
        $column = self::getColumnPersonalSettings($model->id, $userId);
        $column->frozen = $frozen;
        $column->save();
        return $column;
    }
}
