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
    public function actionCheck(){
        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (BiconnectorTokens::isValidToken($token)) {
            return '';
        }else{
            throw new HttpException(403, 'В доступе отказано');
        }
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    public function actionTableList(){

        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (!BiconnectorTokens::isValidToken($token)) {
            throw new HttpException(403, 'В доступе отказано');
        }

        $biconnectorTables = BiconnectorTables::find()->all();
        $data = [];
        foreach ($biconnectorTables as $table){
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
    public function actionTableDescription(){
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
            'tinyint' => 'int',
            'smallint' => 'int',
            'integer' => 'int',
            'bigint' => 'int',
            'float' => 'double',
            'double' => 'double',
            'decimal' => 'double',
            'char' => 'string',
            'varchar' => 'string',
            'text' => 'string',
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'datetime',
        ];

        $result = [];

        // Проходим по всем столбцам таблицы
        foreach ($tableSchema->columns as $column) {
            // Определяем тип столбца
            $dbType = $column->dbType;
            $baseType = $column->type;

            // Преобразуем тип в требуемый формат
            $mappedType = $typeMapping[$baseType] ?? 'string'; // По умолчанию string

            // Формируем результат
            $result[] = [
                'code' => strtoupper($column->name),
                'title' => $column->name, // Если title не задан, используем имя столбца
                'type' => $mappedType,
            ];
        }

        return $result;
    }

    /**
     * @return mixed
     * @throws HttpException
     */
    public function actionData(){
        $token = ArrayHelper::getValue(Yii::$app->request->post('connection'), 'token');
        if (!BiconnectorTokens::isValidToken($token)) {
            throw new HttpException(403, 'В доступе отказано');
        }

        // Получаем параметры из POST-запроса
        $request = \Yii::$app->request;
        $select = $request->post('select', '*'); // Поля для выборки
        $filter = $request->post('filter', []);  // Фильтр
        $limit = $request->post('limit');  // Лимит
        $table = $request->post('table', '');  // Лимит

        // Выполняем запрос к базе данных
        $query = (new Query())
            ->from($table) // Замените на имя вашей таблицы
            ->select($select);

        // Применяем фильтр, если он передан
        if (!empty($filter)) {
            foreach ($filter as $field => $value) {
                $query->andWhere([$field => $value]);
            }
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
        $fields = array_keys($rows[0]); // Имена полей
        $result = [$fields]; // Первый элемент — заголовки полей

        // Добавляем строки значений
        foreach ($rows as $row) {
            $result[] = array_values($row);
        }

        return $result;
    }
}
