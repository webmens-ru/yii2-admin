<?php

namespace wm\admin\models\gii;

use wm\admin\models\ui\Entity;
use wm\admin\models\ui\grid\GridColumn;
use Yii;
use yii\base\Model;
use yii\db\Connection;
use yii\db\Schema;

class Column extends Model
{

    public $tableName;
    public $entityCode;

    /**
     * {@inheritdoc}
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
                    $type = 'number';
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_DATETIME:
                    $type = 'date';
                    break;
                case Schema::TYPE_TIME:
                case Schema::TYPE_TIMESTAMP:
                case Schema::TYPE_JSON:
                    $type = 'string';
                    break;
                default:
                    $type = 'string';
            }
            $this->addGridColumn(
                [
                    'type' => $type,
                    'name' => $column->name,
                    'comment' => $column->comment ? $column->comment : $column->name,
                ],
                $this->entityCode
            );
        }
    }

    public function addGridColumn($column, $entityCode)
    {
        $maxOrderColumn = GridColumn::find()
            ->where(['entityCode' => $entityCode])
            ->limit(1)
            ->orderBy(['order' => SORT_DESC])
            ->one();
        if ($maxOrderColumn) {
            $order = $maxOrderColumn->order + 1;
        } else {
            $order = 1;
        }

        $model = new GridColumn();
        $model->title = $column['comment'];
        $model->entityCode = $entityCode;
        $model->code = $column['name'];
        $model->type = $column['type'];
        $model->order = $order;
        $model->visible = 1;
        $model->width = 100;
        $model->save();
        if ($model->errors) {
            Yii::error($model->errors, 'addFilterField $model->errors');
        }

    }


    /**
     * {@inheritdoc}
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
