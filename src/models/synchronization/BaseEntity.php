<?php

namespace wm\admin\models\synchronization;

use wm\admin\models\gii\ColumnSchema;
use wm\yii\db\ActiveRecord;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 *
 */
class BaseEntity extends ActiveRecord
{
    /**
     * @var string
     */
    public static $primaryKeyColumnName = 'id';

    /**
     * @var string
     */
    public static $synchronizationFullListJob = '';

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = '';

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = '';

    /**
     * @var string
     */
    public static $synchronizationDiffJob = '';

    /**
     * @param string[] $addFieldNames
     * @return true
     * @throws \yii\db\Exception
     */
    public static function createColumns(array $addFieldNames)
    {
        $fields = static::getB24Fields();
        $table = Yii::$app->db->getTableSchema(static::tableName());
        foreach ($fields as $fieldName => $field) {
            if (!isset($table->columns[$fieldName]) && in_array($fieldName, $addFieldNames)) {
                Yii::warning($field, '$field');
                Yii::$app
                    ->db
                    ->createCommand()
                    ->addColumn(
                        $table->name,
                        $fieldName,
                        ColumnSchema::getDbType($field)
                    )
                    ->execute();
                Yii::$app
                    ->db
                    ->createCommand()
                    ->addCommentOnColumn(
                        $table->name,
                        $fieldName,
                        ArrayHelper::getValue($field, 'formLabel') ?: ArrayHelper::getValue($field, 'title')
                    )
                    ->execute();
            }
        }
        return true;
    }

    /**
     * @param string $fieldName
     * @return true
     * @throws \yii\db\Exception
     */
    public static function deleteColumn($fieldName)
    {
        $table = Yii::$app->db->getTableSchema(static::tableName());
        if (ArrayHelper::getValue($table, 'columns.' . $fieldName)) {
            $res = Yii::$app
                ->db
                ->createCommand()
                ->dropColumn(
                    $table->name,
                    $fieldName
                )
                ->execute();
        }
        return true;
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [[$this->attributes(), 'safe']];
    }

    /**
     * @return bool|int|string|null
     */
    public static function getCountDb()
    {
        if (!Yii::$app->db->getTableSchema(static::tableName())) {
            return null;
        }
        return static::find()->count();
    }

    /**
     * @param int $synchronizationEntityId
     * @return void
     * @throws \yii\db\Exception
     */
    public static function createTable($synchronizationEntityId)
    {
        Yii::$app
            ->db
            ->createCommand()
            ->createTable(
                static::tableName(),
                [static::$primaryKeyColumnName => Schema::TYPE_INTEGER,]
            )
            ->execute();
        Yii::$app
            ->db
            ->createCommand()
            ->addPrimaryKey(
                static::$primaryKeyColumnName,
                static::tableName(),
                static::$primaryKeyColumnName
            )
            ->execute();
        Yii::$app
            ->db
            ->createCommand()
            ->insert(
                'admin_synchronization_field',
                [
                    'name' => static::$primaryKeyColumnName,
                    'synchronizationEntityId' => $synchronizationEntityId,
                    'title' => static::$primaryKeyColumnName,
                    'noDelete' => 1
                ]
            )
            ->execute();
    }

    /**
     * @param string $method
     * @param string $dateTimeStart
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function addJobFull($method, $dateTimeStart = null)
    {
        $delay = 0;
        if ($dateTimeStart) {
            $diff = strtotime($dateTimeStart) - time();
            if ($diff > 0) {
                $delay = $diff;
            }
        }

        $objFullSync = null;

        switch ($method) {
            case 'list':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class
                    ]
                );
                break;
            case 'get':
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullGetJob,
                        'modelClass' => static::class
                    ]
                );
                break;
            default:
                $objFullSync = Yii::createObject(
                    [
                        'class' => static::$synchronizationFullListJob,
                        'modelClass' => static::class
                    ]
                );
        }


        $id = Yii::$app->queue->delay($delay)->ttr(3600)->push($objFullSync);
        return $id;
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function synchronization()
    {
        $id = Yii::$app->queue->push(
            Yii::createObject(
                [
                    'class' => static::$synchronizationDeltaJob,
                    'modelClass' => static::class
                ]
            )
        );

        return $id;
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function synchronizationDiff(){
        $id = Yii::$app->queue->push(
            Yii::createObject(
                [
                    'class' => static::$synchronizationDiffJob,
                    'modelClass' => static::class
                ]
            )
        );

        return $id;
    }

    /**
     * @return mixed[]
     */
    public static function getB24Fields()
    {
        return [];
    }

    /**
     * @param int $synchronizationEntityId
     * @return void
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteUnusedFields($synchronizationEntityId)
    {
        $model = Yii::createObject(['class' => static::class]);
        $b24Fields = static::getB24Fields();
        $attributes = $model->attributes();
        foreach ($attributes as $attribute) {
            if (!ArrayHelper::getValue($b24Fields, $attribute)) {
                $column = SynchronizationField::find()
                    ->where(
                        [
                            'synchronizationEntityId' => $synchronizationEntityId,
                            'name' => $attribute
                        ]
                    )
                    ->one();
                $column->delete();
            }
        }
    }
}
