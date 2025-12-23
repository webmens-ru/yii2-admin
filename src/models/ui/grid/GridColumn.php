<?php

namespace wm\admin\models\ui\grid;

use wm\admin\models\ui\Entity;
use Yii;

/**
 * This is the model class for table "grid_column".
 *
 * @property int $id
 * @property string $entityCode
 * @property string $code
 * @property string $title
 * @property int $visible
 * @property int $order
 * @property int $width
 * @property string $type
 * @property int $frozen
 * @property int $reordering
 * @property int $resizeble
 * @property int $sortable
 * @property int $editable
 * @property int $editor
 * @property string|null $info
 * @property string|null $color
 *
 * @property Entity $entityCode0
 * @property GridColumnPersonal[] $gridColumnPersonals
 */
class GridColumn extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_grid_column';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['entityCode', 'code', 'title', 'visible', 'order', 'width', 'type'], 'required'],
            [['visible', 'order', 'width'], 'integer'],
            [['entityCode'], 'string', 'max' => 64],
            [['code', 'type', 'color'], 'string', 'max' => 32],
            [['title', 'info'], 'string', 'max' => 255],
            [['editor'], 'safe'],
            [[
                'frozen',
                'reordering',
                'resizeble',
                'sortable',
                'editable'
            ], 'boolean'],
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
    public function fields()
    {
        return [
            'id',
            'entityCode',
            'code',
            'title',
            'visible' => function () {
                return self::getBooleanValue($this->visible);
            },
            'order',
            'width',
            'type',
            'frozen' => function () {
                return self::getBooleanValue($this->frozen);
            },
            'reordering' => function () {
                return self::getBooleanValue($this->reordering);
            },
            'resizeble' => function () {
                return self::getBooleanValue($this->resizeble);
            },
            'sortable' => function () {
                return self::getBooleanValue($this->sortable);
            },
            'editable' => function () {
                return self::getBooleanValue($this->editable);
            },
            'editor',
            'editorProps',
            'info',
            'color',
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
            'code' => 'Code',
            'title' => 'Title',
            'visible' => 'Visible',
            'order' => 'Order',
            'width' => 'Ширина',
            'type' => 'Тип',
            'frozen' => 'Frozen',
            'reordering' => 'Reordering',
            'resizeble' => 'Resizeble'
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

    public function getGridColumnPersonals()// @phpstan-ignore-line
    {
        return $this->hasMany(GridColumnPersonal::class, ['columnId' => 'id']);
    }

    /**
     * @param string $entityCode
     * @param int $userId
     * @return mixed[]
     */
    public static function getColumns($entityCode, $userId)
    {
        if (!Entity::find()->where(['code' => $entityCode])->one()) {
            Yii::error('$entityCode не содержится в Базе данных');
            return [];
        }

        $models = self::find()->where(['entityCode' => $entityCode])->all();
        $res = [];
        foreach ($models as $value) {
            /** @phpstan-ignore-next-line */
            $settings = $value->getGridColumnPersonals()->andWhere(['userId' => $userId])->one(); // @phpstan-ignore-line
            if ($settings) {
                $value->order = $settings->order;
                $value->visible = $settings->visible;
                $value->width = $settings->width;
                $value->frozen = $settings->frozen;
            }
            $res[] = $value;
        }
        return $res;
    }
}
