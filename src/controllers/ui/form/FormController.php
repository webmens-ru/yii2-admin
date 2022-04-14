<?php

namespace wm\admin\controllers\ui\form;

use wm\admin\models\ui\form\Form;

/**
 * Class FormController
 * @package wm\admin\controllers\ui\form
 *
 * Класс для работы формой
 *
 * Позволяет получить форму с ее полями
 *
 * @package wm\admin\controllers\ui\form
 * @method actionView()
 *
 * Получение формы с ее полями
 *
 * Для получения формы необходимы в query-параметрах передать следующие данные
 *
 * ```php
 * //id- Идентификатор формы
 *
 * /view?id=1
 * ```
 *
 */
class FormController extends \wm\admin\controllers\ActiveRestController
{
    /**
     * @var string
     */
    public $modelClass = Form::class;
}