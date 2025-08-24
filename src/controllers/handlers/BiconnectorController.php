<?php

namespace wm\admin\controllers\handlers;

use wm\admin\models\settings\biconnectors\BiconnectorSettings;
use wm\admin\models\settings\biconnectors\BiconnectorTables;
use wm\admin\models\settings\biconnectors\BiconnectorTokens;
use wm\yii\helpers\ArrayHelper;
use Yii;
use yii\db\Query;
use yii\rest\Controller;
use yii\web\HttpException;

/**
 *
 */
class BiconnectorController extends Controller
{
    /**
     * @return string
     * @throws HttpException
     */
    public function actionCheck()
    {
        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (BiconnectorTokens::isValidToken($token)) {
            return '';
        } else {
            throw new HttpException(403, 'В доступе отказано');
        }
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    public function actionTableList()
    {
        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (!BiconnectorTokens::isValidToken($token)) {
            throw new HttpException(403, 'В доступе отказано');
        }

        $biconnectorTables = BiconnectorTables::find()->all();
        $data = [];
        foreach ($biconnectorTables as $table) {
            $data[] = [
                'code' => $table->name,
                'title' => $table->title
            ];
        }
        return $data;
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    public function actionTableDescription()
    {
        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (!BiconnectorTokens::isValidToken($token)) {
            throw new HttpException(403, 'В доступе отказано');
        }
        $tableName = Yii::$app->request->post('table');
        return $this->getTableColumns($tableName);
    }

    /**
     * @param string $tableName
     * @return mixed
     * @throws \Exception
     */
    private function getTableColumns($tableName)
    {
        // Получаем экземпляр подключения к базе данных
        $db = \Yii::$app->getDb();

        // Получаем метаданные таблицы
        $tableSchema = $db->getTableSchema($tableName);

        if ($tableSchema === null) {
            throw new \Exception("Table '{$tableName}' not found.");
        }

        // Маппинг типов MySQL на требуемые типы
        $typeMapping = [
            'tinyint' => 'INT',
            'smallint' => 'INT',
            'integer' => 'INT',
            'bigint' => 'INT',
            'float' => 'DOUBLE',
            'double' => 'DOUBLE',
            'decimal' => 'DOUBLE',
            'char' => 'STRING',
            'varchar' => 'STRING',
            'text' => 'STRING',
            'date' => 'DATE',
            'datetime' => 'DATETIME',
            'timestamp' => 'DATETIME',
        ];

        $result = [];

        // Проходим по всем столбцам таблицы
        foreach ($tableSchema->columns as $column) {
            // Определяем тип столбца
            $dbType = $column->dbType;
            $baseType = $column->type;

            // Преобразуем тип в требуемый формат
            $mappedType = $typeMapping[$baseType] ?? 'STRING'; // По умолчанию string

            // Формируем результат
            $result[] = [
                'code' => strtoupper($column->name),
                'name' => strtoupper($column->name), // Если title не задан, используем имя столбца
                'type' => $mappedType,
            ];
        }
        return $result;
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    public function actionData()
    {
        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (!BiconnectorTokens::isValidToken($token)) {
            throw new HttpException(403, 'В доступе отказано');
        }

        // Получаем параметры из POST-запроса
        $request = \Yii::$app->request;
        $select = $request->post('select', '*'); // Поля для выборки
        $filter = $request->post('filter', []);  // Фильтр
        $limit = $request->post('limit');  // Лимит
        $table = $request->post('table', '');  // Таблица

        // Выполняем запрос к базе данных
        $query = (new Query())
            ->from($table)
            ->select($select);

        // Применяем фильтр, если он передан
        if (!empty($filter)) {
            $this->applyFilter($query, $filter);
        }

        // Применяем лимит, если он передан
        if ($limit != 0) {
            $query->limit($limit);
        }

        // Выполняем запрос и получаем данные
        $rows = $query->all();

        // Если данные пустые, возвращаем пустой массив
        if (empty($rows)) {
            return [];
        }

        // Формируем структуру ответа
        $fields = array_map('strtoupper', array_keys($rows[0])); //@phpstan-ignore-line
        $result = [$fields]; // Первый элемент — заголовки полей

        // Добавляем строки значений
        foreach ($rows as $row) {
            $result[] = array_values($row);
        }

        return $result;
    }

    /**
     * @param Query $query
     * @param mixed[] $filter
     * @return void
     */
    protected function applyFilter(Query $query, array $filter)
    {
        foreach ($filter as $key => $condition) {
            if (is_numeric($key)) {
                // Обработка сложных условий с логикой (OR/AND)
                if (isset($condition['LOGIC'])) {
                    $logic = $condition['LOGIC'];
                    unset($condition['LOGIC']);

                    $nestedConditions = [];
                    foreach ($condition as $nestedCondition) {
                        $nestedConditions[] = $this->buildCondition($nestedCondition);
                    }

                    if ($logic === 'OR') {
                        $query->orWhere(array_merge(['or'], $nestedConditions));
                    } else {
                        $query->andWhere(array_merge(['and'], $nestedConditions));
                    }
                }
            } else {
                // Обработка простых условий
                $query->andWhere($this->buildCondition([$key => $condition]));
            }
        }
    }

    /**
     * @param mixed[] $condition
     * @return mixed
     */
    protected function buildCondition(array $condition)
    {
        $result = [];

        foreach ($condition as $field => $value) {
            // Определяем оператор сравнения
            $operator = '=';
            $cleanField = $field;

            if (preg_match('/^(>=|<=|>|<|!=|=)(.+)/', $field, $matches)) {
                $operator = $matches[1];
                $cleanField = $matches[2];
            }

            if ($operator === '=' && is_array($value)) {
                $result[] = ['in', $cleanField, $value];
            } else {
                $result[] = [$operator, $cleanField, $value];
            }
        }

        return count($result) === 1 ? $result[0] : array_merge(['and'], $result);
    }
}
