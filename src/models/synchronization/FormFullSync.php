<?php

namespace wm\admin\models\synchronization;

use wm\admin\models\Synchronization;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class FormSync
 * @package wm\admin\models\synchronization
 */
class FormFullSync extends Model
{
    public const METHOD_LIST = [
        ['id' => 'get', 'title' => 'get'],
        ['id' => 'list', 'title' => 'list'],
    ];

    /**
     * @var
     */
    public $entityId;
    /**
     * @var
     */
    public $dateTimeStart;

    public $method;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['entityId', 'method'], 'required'],
            [
                ['entityId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Synchronization::class,
                'targetAttribute' => ['entityId' => 'id']
            ],
            [['entityId'], 'integer'],
            [['dateTimeStart'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['dateTimeStart'], function ($attribute, $params) {
                if ($this->$attribute) {
                    $diff = strtotime($this->$attribute) - time();
                    if ($diff < 0) {
                        $this->addError($attribute, 'Вы указали дату и время которые уже прошли');
                    }
                }
            }],
            [['method'], 'string'],
            [['method'], function ($attribute, $params) {
                if (!in_array($this->$attribute, ArrayHelper::getColumn(self::METHOD_LIST, 'id'))) {
                    $this->addError($attribute, 'Выберите правильно значение из списка');
                }
            }]
        ];
    }
}
