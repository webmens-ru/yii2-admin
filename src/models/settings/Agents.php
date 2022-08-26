<?php

namespace wm\admin\models\settings;

use Yii;

/**
 * This is the model class for table "baseapp_agents".
 *
 * @property int $id
 * @property string $name
 * @property string $class
 * @property string $method
 * @property string $params
 * @property string $date_run
 * @property int $period
 */
class Agents extends \yii\db\ActiveRecord
{
    const SCENARIO_ONLY_TIME_SETTINGS = 'onlyTimeSettings';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ONLY_TIME_SETTINGS] = [
            'minuteTypeId',
            'hourTypeId',
            'dayTypeId',
            'monthTypeId',
            'finishTypeId',
            'minuteProps',
            'hourProps',
            'dayProps',
            'monthProps',
            'finishProps',
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_agents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'class', 'method', 'params', 'date_run', 'status_id'], 'required'],
            [['params'], 'string'],
            [['date_run'], 'safe'],
            [['period', 'status_id'], 'integer'],
            [['name', 'class'], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 64],
            [
                [
                    'minuteTypeId',
                    'hourTypeId',
                    'dayTypeId',
                    'monthTypeId',
                    'finishTypeId'
                ],
                'required'
            ],

            //minuteProps
            [
                'minuteProps',
                'required',
                'when' => function ($model) {
                    return $model->minuteTypeId > 1;
                },
                'whenClient' => "function (attribute, value) {}"
                ///*function (attribute, value) {
                //                    console.log($('#minuteTypeId').val());
                //                    return $('#minuteTypeId').val() > 1;
                //                }*/
            ],
            [
                'minuteProps',
                'integer',
                'min' => 1,
                'max' => 59,
                'when' => function ($model) {
                    return $model->minuteTypeId == 2;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'minuteProps',
                function ($attribute, $params) {
                    if (!preg_match('/^(?:\d+\,)+\d+?$/', $this->$attribute)) {
                        $this->addError($attribute, "в параметре содержатся недопустимые символы");
                    }
                    $values = explode(',', $this->$attribute);
                    foreach ($values as $value) {
                        if ($value < 0 || $value > 59) {
                            $this->addError($attribute, "число $value не входит в допустимый диапазон 0-59");
                        }
                    }
                },
                'when' => function ($model) {
                    return $model->minuteTypeId == 3;
                },
                'whenClient' => "function (attribute, value) {}"
            ],

            //hourProps
            [
                'hourProps',
                'required',
                'when' => function ($model) {
                    return $model->hourTypeId > 1;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'hourProps',
                'integer',
                'min' => 1,
                'max' => 23,
                'when' => function ($model) {
                    return $model->hourTypeId == 2;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'hourProps',
                function ($attribute, $params) {
                    if (!preg_match('/^(?:\d+\,)+\d+?$/', $this->$attribute)) {
                        $this->addError($attribute, "в параметре содержатся недопустимые символы");
                    }
                    $values = explode(',', $this->$attribute);
                    foreach ($values as $value) {
                        if ($value < 0 || $value > 23) {
                            $this->addError($attribute, "число $value не входит в допустимый диапазон 0-23");
                        }
                    }
                },
                'when' => function ($model) {
                    return $model->hourTypeId == 3;
                },
                'whenClient' => "function (attribute, value) {}"
            ],

            //dayProps
            [
                'dayProps',
                'required',
                'when' => function ($model) {
                    return $model->dayTypeId > 1;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'dayProps',
                'integer',
                'min' => 1,
                'max' => 31,
                'when' => function ($model) {
                    return $model->dayTypeId == 2;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'dayProps',
                function ($attribute, $params) {
                    if (!preg_match('/^(?:\d+\,)+\d+?$/', $this->$attribute)) {
                        $this->addError($attribute, "в параметре содержатся недопустимые символы");
                    }
                    $values = explode(',', $this->$attribute);
                    foreach ($values as $value) {
                        if ($value < 1 || $value > 31) {
                            $this->addError($attribute, "число $value не входит в допустимый диапазон 1-31");
                        }
                    }
                },
                'when' => function ($model) {
                    return $model->dayTypeId == 3;
                },
                'whenClient' => "function (attribute, value) {}"
            ],

            //monthProps
            [
                'monthProps',
                'required',
                'when' => function ($model) {
                    return $model->monthTypeId > 1;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'monthProps',
                'integer',
                'min' => 1,
                'max' => 12,
                'when' => function ($model) {
                    return $model->monthTypeId == 2;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'monthProps',
                function ($attribute, $params) {
                    if (!preg_match('/^(?:\d+\,)+\d+?$/', $this->$attribute)) {
                        $this->addError($attribute, "в параметре содержатся недопустимые символы");
                    }
                    $values = explode(',', $this->$attribute);
                    foreach ($values as $value) {
                        if ($value < 1 || $value > 12) {
                            $this->addError($attribute, "число $value не входит в допустимый диапазон 1-12");
                        }
                    }
                },
                'when' => function ($model) {
                    return $model->monthTypeId == 3;
                },
                'whenClient' => "function (attribute, value) {}"
            ],

            //finishProps
            [
                'finishProps',
                'required',
                'when' => function ($model) {
                    return $model->finishTypeId > 1;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
            [
                'monthProps',
                'datetime',
                'when' => function ($model) {
                    return $model->monthTypeId == 2;
                },
                'whenClient' => "function (attribute, value) {}"
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'class' => 'Class',
            'method' => 'Method',
            'params' => 'Params',
            'date_run' => 'Date Run',
            'period' => 'Period',
            'status_id' => 'Status',
        ];
    }

    public static function shedulRun()
    {
        $dateTimestamp = date("Y-m-d H:i:s");

        $models = self::find()->where(['<=', 'date_run', $dateTimestamp])->andWhere(['status_id' => 1])->all();
        foreach ($models as $model) {
            try {
                call_user_func(array($model->class, $model->method));
            } catch (\Exception $e) {
            }
            $timestamp = strtotime($dateTimestamp) + $model->period;
            $model->date_run = date("Y-m-d H:i:s", $timestamp);
            $model->save();
        }
    }
}
