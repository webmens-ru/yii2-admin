<?php

namespace wm\admin\models\gii;

use wm\admin\models\ui\Entity;
use wm\admin\models\ui\filter\Filter as UiFilter;
use wm\admin\models\ui\filter\FilterField;
use Yii;
use yii\base\Model;
use yii\db\Connection;
use yii\db\Schema;

class Filter extends Model
{

    public $tableName;
    public $entityCode;

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['tableName', 'entityCode'], 'required'],
            [['tableName'], 'filter', 'filter' => 'trim', 'skipOnEmpty' => true],
            [['tableName'], 'match', 'pattern' => '/^([\w ]+\.)?([\w\* ]+)$/', 'message' => 'Only word characters, and optionally spaces, an asterisk and/or a dot are allowed.'],
            [['tableName'], 'validateTableName'],
            [
                ['entityCode'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Entity::class,
                'targetAttribute' => ['entityCode' => 'code']
            ],
        ];
    }

    public function generate()
    {
        UiFilter::addBasic($this->entityCode);

        $db = $this->getDbConnection();
        $tableSchema = $db->getTableSchema($this->tableName);

        $properties = [];
        foreach ($tableSchema->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_TINYINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $type = 2;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_DATETIME:
                    $type = 3;
                    break;
                case Schema::TYPE_TIME:
                case Schema::TYPE_TIMESTAMP:
                case Schema::TYPE_JSON:
                    $type = 1;
                    break;
                default:
                    $type = 1;
            }
            $this->addFilterField(
                [
                    'type' => $type,
                    'name' => $column->name,
                    'comment' => $column->comment ? $column->comment : $column->name,
                ],
                $this->entityCode
            );
        }
    }

    public function addFilterField($column, $entityCode)
    {
        $filterField = FilterField::find()
            ->where(['entityCode' => $entityCode])
            ->limit(1)
            ->orderBy(['order' => SORT_DESC])
            ->one();
        if ($filterField) {
            $order = $filterField->order + 1;
        } else {
            $order = 1;
        }

        $model = new FilterField();
        $model->title = $column['comment'];
        $model->entityCode = $entityCode;
        $model->typeId = $column['type'];
        $model->order = $order;
        $model->code = $column['name'];
        $model->save();
        if ($model->errors) {
            Yii::error($model->errors, 'addFilterField $model->errors');
        }

    }


    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'tableName' => 'Таблица',
            'entityCode' => 'Entity',
        ];
    }

    /**
     * Returns the database connection as specified by [[db]].
     *
     * @return Connection|null database connection instance
     */
    protected function getDbConnection()
    {
        return Yii::$app->get('db', false);
    }

    public function getTableNames()
    {
        $db = $this->getDbConnection();

        if ($db !== null) {
            return $db->getSchema()->getTableNames();
        }

        return [];
    }


    /**
     * Validates the [[tableName]] attribute.
     */
    public function validateTableName()
    {
        if (strpos($this->tableName, '*') !== false && substr_compare($this->tableName, '*', -1, 1)) {
            $this->addError('tableName', 'Asterisk is only allowed as the last character.');

            return;
        }
        $tables = $this->getTableNames();
        if (!in_array($this->tableName, $tables)) {
            $this->addError('tableName', "Table '{$this->tableName}' does not exist.");
        }
    }
}
