<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\jobs\employee\EmployeeSynchronizationFullListJob;
use wm\admin\jobs\employee\EmployeeSynchronizationFullGetJob;
use wm\admin\jobs\employee\EmployeeSynchronizationDeltaJob;
use wm\b24tools\b24Tools;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use wm\admin\models\gii\ColumnSchema;

class Employee extends BaseEntity implements SynchronizationInterface
{
    public static function tableName()
    {
        return 'sync_employee';
    }

    public static $synchronizationFullListJob = EmployeeSynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = EmployeeSynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = EmployeeSynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'ID';

    public static function getCountB24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $request = $b24Obj->client->call(
            'user.get',
        );
        return $request['total'];
    }

    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        $key = 'user.fields';
        $fields = $cache->getOrSet($key, function () {
            $component = new b24Tools();
            $b24App = $component->connectFromAdmin();
            $b24Obj = new B24Object($b24App);
            $data = ArrayHelper::getValue($b24Obj->client->call(
                'user.fields'
            ), 'result');
            return $data;
        }, 300);
        return $fields;
    }

    public static function getB24FieldsList()
    {
        return self::getB24Fields();
    }

    public static function startSynchronization($period)
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты пользователи';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 03:00:00';
        }
        $agent->period = $period;
        $agent->status_id = 1;
        $agent->save();
    }

    public static function stopSynchronization()
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if ($agent) {
            $agent->status_id = 0;
            $agent->save();
        }
    }

    public function loadData($data)
    {
        foreach ($data as $key => $val) {
            if (in_array($key, array_keys($this->attributes))) {
                is_array($val) ? $this->$key = json_encode($val) : $this->$key = $val;
            }
        }
        $this->save();
        if ($this->errors) {
            Yii::error($this->errors, 'Employee->loadData()');
        }
    }

    public static function createColumns(array $addFieldNames)
    {
        $fields = static::getB24Fields();
        $table = Yii::$app->db->getTableSchema(static::tableName());
        foreach ($fields as $fieldName => $fieldLable) {
            if (!isset($table->columns[$fieldName]) && in_array($fieldName, $addFieldNames)) {
                Yii::$app
                    ->db
                    ->createCommand()
                    ->addColumn(
                        $table->name,
                        $fieldName,
                        static::getDbType($fieldLable)
                    )
                    ->execute();
            }
        }
        return true;
    }

    public static function getDbType($lable)
    {
        switch ($lable) {
            case 'ID':
                return Schema::TYPE_INTEGER;
            case 'XML_ID':
                return Schema::TYPE_INTEGER;
            case 'ACTIVE':
                return Schema::TYPE_BOOLEAN;
            case 'NAME':
                return Schema::TYPE_STRING . '(32)';
            case 'LAST_NAME':
                return Schema::TYPE_STRING . '(32)';
            case 'SECOND_NAME':
                return Schema::TYPE_STRING . '(32)';
            case 'EMAIL':
                return Schema::TYPE_STRING;
            case 'LAST_LOGIN':
                return Schema::TYPE_DATE;
            case 'DATE_REGISTER':
                return Schema::TYPE_INTEGER;
            case 'IS_ONLINE':
                return Schema::TYPE_STRING . '(8)';
            case 'PERSONAL_GENDER':
                return Schema::TYPE_STRING . '(8)';
            case 'PERSONAL_WWW':
                return Schema::TYPE_STRING;
            case 'PERSONAL_BIRTHDAY':
                return Schema::TYPE_DATE;
            case 'PERSONAL_PHOTO':
                return Schema::TYPE_STRING;
            case 'PERSONAL_MOBILE':
                return Schema::TYPE_STRING . '(32)';
            case 'PERSONAL_CITY':
                return Schema::TYPE_STRING . '(32)';
            case 'PERSONAL_STATE':
                return Schema::TYPE_STRING;
            case 'PERSONAL_ZIP':
                return Schema::TYPE_STRING;
            case 'PERSONAL_COUNTRY':
                return Schema::TYPE_STRING;
            case 'PERSONAL_MAILBOX':
                return Schema::TYPE_STRING;
            case 'UF_EMPLOYMENT_DATE':
                return Schema::TYPE_STRING;

            default:
                return Schema::TYPE_STRING;
        }
    }
}
