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
use yii\base\Exception;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use wm\admin\models\gii\ColumnSchema;

/**
 *
 */
class Employee extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_employee';
    }

    /**
     * @var string
     */
    public static $synchronizationFullListJob = EmployeeSynchronizationFullListJob::class;

    /**
     * @var string
     */
    public static $synchronizationDeltaJob = EmployeeSynchronizationDeltaJob::class;

    /**
     * @var string
     */
    public static $synchronizationFullGetJob = EmployeeSynchronizationFullGetJob::class;

    /**
     * @var string
     */
    public static $primaryKeyColumnName = 'ID';

    /**
     * @return mixed
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
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

    /**
     * @return array|mixed[]
     */
    public static function getB24Fields()
    {
        $cache = Yii::$app->cache;
        if(!$cache){
            throw new Exception('Cache not found');
        }
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
        $result = [];
        foreach ($fields as $key => $value) {
            $result[$key] = ['id' => $key, 'title' => $value];
        }
        return $result;
    }

    /**
     * @return array|mixed[]
     */
    public static function getB24FieldsList()
    {
        return ArrayHelper::map(self::getB24Fields(), 'id', 'title');
    }

    /**
     * @param $modelAgentTimeSettings
     * @return void
     */
    public static function startSynchronization($modelAgentTimeSettings)
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
        $agent->load(ArrayHelper::toArray($modelAgentTimeSettings), '');
        $agent->status_id = 1;
        $agent->save();
    }

    /**
     * @return void
     */
    public static function stopSynchronization()
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if ($agent) {
            $agent->status_id = 0;
            $agent->save();
        }
    }

    /**
     * @param $data
     * @return void
     */
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

    /**
     * @param string[] $addFieldNames
     * @return true
     * @throws \yii\db\Exception
     */
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

    /**
     * @param string $lable
     * @return string
     */
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
