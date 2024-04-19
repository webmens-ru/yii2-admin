<?php

namespace wm\admin\models\synchronization;

use wm\admin\jobs\crmcategory\CrmCategorySynchronizationDeltaJob;
use wm\admin\jobs\crmcategory\CrmCategorySynchronizationFullGetJob;
use wm\admin\jobs\crmcategory\CrmCategorySynchronizationFullListJob;
use wm\admin\models\settings\Agents;
use Yii;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class CrmCategory extends BaseEntity implements SynchronizationInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sync_crm_category';
    }

    public static $synchronizationFullListJob = CrmCategorySynchronizationFullListJob::class;

    public static $synchronizationDeltaJob = CrmCategorySynchronizationDeltaJob::class;

    public static $synchronizationFullGetJob = CrmCategorySynchronizationFullGetJob::class;

    public static $primaryKeyColumnName = 'id';


    public static function getCountB24()
    {
//        $component = new b24Tools();
//        $b24App = $component->connectFromAdmin();
//        $b24Obj = new B24Object($b24App);
//        $request = $b24Obj->client->call(
//            'crm.category.list',
//            ['entityTypeId' => 142]// направления сделок
//        );
//        return $request['total'];
        return 'Не рассчитывается';
    }

    public static function getB24Fields()
    {
        return [
            'id' => ['id' => "id", 'title' => 'id'],
            'name' => ['id' => "name", 'title' => 'Название'],
            'sort' => ['id' => "sort", 'title' => 'Сортировка'],
            'entityTypeId' => ['id' => "entityTypeId", 'title' => 'entityTypeId'],
            'isDefault' => ['id' => "isDefault", 'title' => 'isDefault'],
            'entityTypeName' => ['id' => "entityTypeName", 'title' => 'entityTypeName'],
        ];
    }

    public static function getB24FieldsList()
    {
        return ArrayHelper::map(self::getB24Fields(), 'id', 'title');
    }

    public static function startSynchronization($period)
    {
        $agent = Agents::find()->where(['class' => static::class, 'method' => 'synchronization'])->one();
        if (!$agent) {
            $agent = new Agents();
            $agent->name = 'Синхронизация дельты сделки';
            $agent->class = static::class;
            $agent->method = 'synchronization';
            $agent->params = '-';
            $agent->date_run = '1970-01-01 00:00:00';
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
            Yii::error($this->errors, 'CrmCategory->loadData()');
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
            case 'id':
                return Schema::TYPE_INTEGER;
            case 'entityTypeId':
                return Schema::TYPE_INTEGER;
            case 'sort':
                return Schema::TYPE_INTEGER;
            case 'name':
                return Schema::TYPE_STRING;
            case 'entityTypeName':
                return Schema::TYPE_STRING;
            case 'entityTypeName':
                return Schema::TYPE_BOOLEAN;
            default:
                return Schema::TYPE_STRING;
        }
    }
}
