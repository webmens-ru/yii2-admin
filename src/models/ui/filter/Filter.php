<?php

namespace wm\admin\models\ui\filter;

use wm\admin\models\ui\Entity;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Filter
 * @package wm\admin\models\ui\filter
 *
 * @property int $id
 * @property string $title
 * @property string $entityCode
 * @property int $isName
 * @property int $order
 * @property int $isBase
 * @property int $userId
 * @property int $parentId
 * @property FilterFieldSetting[] $filterFieldSettings
 */
class Filter extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_filter';
    }

    /**
     * @param $insert
     * @param $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if ($this->parentId) {
                FilterFieldSetting::copyField($this->parentId, $this->id);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['entityCode', 'title', 'isName', 'order', 'isBase'], 'required'],
            [['isName', 'order', 'isBase', 'userId'], 'integer'],
            [['entityCode'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 255],
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
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entityCode' => 'Entity Code',
            'title' => 'Title',
            'isName' => 'Is Name',
            'order' => 'Order',
            'isBase' => 'Базовый',
            'userId' => 'Пользователь',
            'parentId' => 'Родительский фильтр',
        ];
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::class, ['code' => 'entityCode']);
    }

    /**
     * @return mixed
     */
    public function getFilterFieldSettings()
    {
        return $this->hasMany(FilterFieldSetting::class, ['filterId' => 'id']);
    }

    //первоначальное построение списка элементов фильтра

    /**
     * @param $entityCode
     * @param $userId
     * @return array
     */
    public static function getItems($entityCode, $userId)
    {

        // проверка на наличие в БД запрошенной сущности
        if (!Entity::find()->where(['code' => $entityCode])->one()) {
            Yii::error('$entityCode не содержится в Базе данных');
            return [];
//return [];
        }

        /* получение из таблицы filter строк у которых ...
         * при первом вхождении пользователя $models будет равен null, т.к. в БД нет еще
         * записи с необходимым userId. Все базовые фильтры имеют userId == null.
         */
        $models = self::find()->where(['entityCode' => $entityCode, 'userId' => $userId])->all(); //
        if (!$models) {
            //при первом вхождении проваливаемся сюда
            self::addBaseItems($entityCode, $userId); //
            $models = self::find()->where(['entityCode' => $entityCode, 'userId' => $userId])->all();
        }
        return $models;
    }

    /* создание фильтров для конкретного пользователя из базовых фильтров (у которых
     * isBase == 1, userId == null, parentId == null)
     * для конкретного пользователя isBase == 0, parentId == id родительского фильтра,
     * userId == id пользователя(присваивается при записи в таблицу user)
     */

    /**
     * @param $entityCode
     * @param $userId
     */
    public static function addBaseItems($entityCode, $userId)
    {

        $models = self::find()->where(['entityCode' => $entityCode, 'isBase' => 1])->all();
        foreach ($models as $value) {
            $model = new Filter();
            /*
             * преобразуем объект в массив с public свойствами и загружаем в новый объект
             */
            $model->load(ArrayHelper::toArray($value));
            $model->isBase = 0; // переопределяем т.к. это уже не базовый фильтр
            $model->userId = $userId; //переопределяем т.к. это уже не базовый фильтр
            $model->parentId = $value->id; //переопределяем на id родителя от которого сделан данный фильтр
            /** @phpstan-ignore-next-line */
            $model->id = null; //переопределяем id для того, чтобы при записи новому фильтру присвоить новый id
            $model->save();
            if ($model->errors) {
                Yii::error($model->errors, 'addBaseItems Filter');
            }
        }
    }

    /**
     * @param $entityCode
     * @param $userId
     * @return Filter
     */
    protected static function getFilter($entityCode, $userId)
    {
        $filter = self::find()->where(['entityCode' => $entityCode, 'userId' => $userId])->one();
        if (!$filter) {
            $filter = new Filter();
            return $filter;
        }
        return $filter;
    }

    /**
     * @param $filterParams
     * @param $userId
     * @return bool|Filter
     */
    public static function add($filterParams, $userId)
    {
        $parentModel = self::find()->where(['id' => $filterParams['parentId'],])->one();
        $model = new Filter();
        $model->load(ArrayHelper::toArray($parentModel));
        $model->id = null;// @phpstan-ignore-line
        $model->parentId = ArrayHelper::getValue($filterParams, 'parentId');
        $model->order = ArrayHelper::getValue($filterParams, 'order');
        $model->isName = 1;
        $model->userId = $userId;
        $model->title = ArrayHelper::getValue($filterParams, 'title');
        $model->save();

        if ($model->errors) {
            Yii::error($model->errors, 'Filter add');
            return false;
        } else {
            return $model;
        }
    }

    /**
     * @param $params
     */
    public static function editOrder($params)
    {
        foreach ($params as $param) {
            $model = self::find()->where(['id' => $param['id'],])->one();
            $model->order = ArrayHelper::getValue($param, 'order');
            $model->save();
            if ($model->errors) {
                Yii::error($model->errors, 'Filter editOrder');
            }
        }
    }

    public static function addBasic($entityCode, $title = 'Безымянный')
    {
        $entity = Entity::find()->where(['code' => $entityCode])->one();
        $baseFilter = self::find()
            ->where(
                [
                    'entityCode' => $entityCode
                ]
            )
            ->all();
        if (!$entity || $baseFilter) {
            return false;
        }
        $model = new Filter();
        $model->title = $title;
        $model->entityCode = $entityCode;
        $model->isName = 0;
        $model->order = 1;
        $model->isBase = 1;
        $model->save();
        if ($model->errors) {
            Yii::error($model->errors, 'addBasic $model->errors');
        }
    }
}
