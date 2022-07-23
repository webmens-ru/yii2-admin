<?php

namespace wm\admin\controllers\ui\filter;

use phpDocumentor\Reflection\Types\Integer;
use wm\admin\models\ui\filter\FilterFieldSettingSearch;
use wm\admin\models\ui\filter\FilterFieldSetting;
use Yii;

/**
 * Класс для работы с параметрами полей фильтра
 *
 * Когда загружается фильтр его поля могут быть заполнены различными значениями и параметрами,
 * для управления данным набором и был создан данный класс
 *
 * @package wm\admin\controllers\ui\filter
 * @method actionCreate()
 *
 * Создание настроек поля фильтра
 *
 * Для создания настроек поля фильтра в теле запроса необходимо передать следующие данные
 *
 * ```php
 * //filterId - Идентификатор фильтра
 * //filterFieldId - Идентификатор поля
 * //order - Сортировка
 * //value - Параметры поля фильтра
 * {
 *     "filterId":12,
 *     "filterFieldId":1,
 *     "value":["Последние 30 дней"]
 *     "order":5
 * }
 * ```
 *
 * @method array actionUpdate(integer $id)
 *
 * Изменение настроек поля фильтра
 *
 * Для изменения настроек поля фильтра в теле запроса необходимо передать следующие данные
 *
 * ```php
 * //filterId - Идентификатор фильтра
 * //filterFieldId - Идентификатор поля
 * //order - Сортировка
 * //value - Параметры поля фильтра
 * {
 *     "filterId":12,
 *     "filterFieldId":1,
 *     "value":["Последние 30 дней"]
 *     "order":5
 * }
 * ```
 *
 * @method array  actionIndex(integer $filterId = null)
 *
 * Получение списка настроек полей фильтра
 *
 * Пример возвращаемых значений
 *
 * ```php *
 * [
 *    {
 *        "id": 17,
 *        "filterId": 7,
 *        "filterFieldId": 4,
 *        "value": [
 *            "=>",
 *            "20",
 *            ""
 *        ],
 *        "title": "",
 *        "order": 0
 *    },
 *    {
 *        "id": 18,
 *        "filterId": 7,
 *        "filterFieldId": 5,
 *        "value": [
 *            "",
 *            "",
 *            ""
 *        ],
 *        "title": "",
 *        "order": 0
 *    }
 * ]
 * ```
 *
 */
class FilterFieldSettingController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = FilterFieldSetting::class;


    /**
     * @var string
     */
    public $modelClassSearch = FilterFieldSettingSearch::class;

    /**
     * Изменение порядка расположения полей фильтра
     *
     * Данный метод вызывается при перетаскивании полей фильтра.
     * Для выполнения данного метода в теле запроса необходимо передать данные в следующей структуре
     *
     * ```php
     * [
     *    {
     *        "id":14,
     *        "order":3
     *    },
     *    {
     *        "id":80,
     *        "order":6
     *    }
     * ]
     * ```
     *
     * @return bool
     */
    public function actionEditOrder()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        FilterFieldSetting::editOrder($params);
        return true;
    }
}
