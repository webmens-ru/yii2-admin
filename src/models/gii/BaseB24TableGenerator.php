<?php

namespace wm\admin\models\gii;

use Yii;
use yii\base\Model;
use yii\db\Schema;

class BaseB24TableGenerator extends Model
{
    public $tableName;
    public $deleteOldTable;
    public $tableFields;

    protected $primaryKeyColumnName = 'id';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tableName', 'tableFields'], 'required'],
            [['tableName'], 'string', 'max' => 32],
            [['deleteOldTable'], 'integer'],
            ['tableFields', 'validateFields'],
        ];
    }

    public function validateFields($attribute, $params)
    {
        if (!in_array($this->primaryKeyColumnName, $this->$attribute)) {
            $this->addError(
                $attribute,
                'поле ' . $this->primaryKeyColumnName . ' Должно быть обязательно выбрано'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tableName' => 'Имя таблицы',
            'deleteOldTable' => 'Удалить таблицу с таким именем при наличии?',
            'tableFields' => 'Поля'
        ];
    }

    public function createTable()
    {
        return $this->createTableInternal($this->primaryKeyColumnName);
    }

    public function getFieldsList()
    {
        $fields = $this->getFields();
        $result = [];
        foreach ($fields as $fieldName => $field) {
            $result[$fieldName] = $field['title'] . ' - ' . $fieldName . '/' . $field['type'];
        }
        return $result;
    }

    protected function createTableInternal($primaryKeyColumnName = 'id')
    {
        if ($this->deleteOldTable && Yii::$app->db->getTableSchema($this->tableName)) {
            Yii::$app->db->createCommand()->dropTable($this->tableName)->execute();
        } elseif (!$this->deleteOldTable && Yii::$app->db->getTableSchema($this->tableName)) {
            return false;
        }
        Yii::$app
            ->db
            ->createCommand()
            ->createTable(
                $this->tableName,
                [$primaryKeyColumnName => Schema::TYPE_INTEGER,]
            )
            ->execute();
        Yii::$app
            ->db
            ->createCommand()
            ->addPrimaryKey(
                $primaryKeyColumnName,
                $this->tableName,
                $primaryKeyColumnName
            )
            ->execute();
        $fields = $this->getFields();
        $table = Yii::$app->db->getTableSchema($this->tableName);
        foreach ($fields as $fieldName => $field) {
            if (!isset($table->columns[$fieldName]) && in_array($fieldName, $this->tableFields)) {
                Yii::$app
                    ->db
                    ->createCommand()
                    ->addColumn(
                        $table->name,
                        $fieldName,
                        ColumnSchema::getDbType($field)
                    )
                    ->execute();
            }
        }
        return true;
    }

    protected function getFields()
    {
        return [];
    }
}
