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
 * @property string $minuteProps
 * @property string $hourProps
 * @property string $dayProps
 * @property string $monthProps
 * @property int $period
 * @property int $status_id
 * @property int $minuteTypeId
 * @property int $hourTypeId
 * @property int $dayTypeId
 * @property int $monthTypeId
 * @property int $finishTypeId
 */
class Agents extends \yii\db\ActiveRecord
{
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
            [['name', 'class', 'method', 'params', 'date_run', 'period', 'status_id'], 'required'],
            [['params'], 'string'],
            [['date_run'], 'safe'],
            [['period', 'status_id',], 'integer'],
            [['name', 'class',], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 64],
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
            if($model->period) {
                $timestamp = strtotime($dateTimestamp) + $model->period;
                $model->date_run = date("Y-m-d H:i:s", $timestamp);
                $model->save();
            } else {
                $nextMinuteData = self::getNextMinute($dateTimestamp, $model->minuteTypeId, $model->minuteProps);
                $nextHourData = self::getNextHour($nextMinuteData['date'], $nextMinuteData['isNextHour'], $model->hourTypeId, $model->hourProps);
                $nextDayData = self::getNextDay($nextHourData['date'], $nextHourData['isNextDay'], $model->dayTypeId, $model->dayProps);
                $model->date_run = self::getNextMonth($nextDayData['date'], $nextDayData['isNextMonth'], $model->monthTypeId, $model->monthProps);
                $model->save();
            }
        }
    }

    private static function getNextMinute($initialDate, $minuteTypeId, $minuteProps = null)
    {
        $isNextHour = false;
        $nextMinute;
        $nextHour;
        $initialDate = strtotime($initialDate);
        switch ($minuteTypeId) {
            case 1:
                $nextMinute = date('i', strtotime('+1 minute', $initialDate));
                if(date('H', $initialDate) != $nextHour = date('H', strtotime("+1 minute", $initialDate))) {
                    $isNextHour = true;
                }
                break;
            case 2:
                $nextMinute = date('i', strtotime("+$minuteProps minute", $initialDate));
                if(date('H', $initialDate) != $nextHour = date('H', strtotime("+$minuteProps minute", $initialDate))) {
                    $isNextHour = true;
                }
                break;
            case 3:
                $arrMinutes = explode(',', $minuteProps);
                $nextMinute = max($arrMinutes);
                if(max($arrMinutes) < date('i', $initialDate)) {
                    $isNextHour = true;
                    $nextMinute = min($arrMinutes);
                } else {
                    $arr = array_filter($arrMinutes, function ($value) {
                        return ($value > date('i', $initialDate));
                    });
                    $nextMinute = min($arr);
                }
                $nextHour = date('H', strtotime("+1 hour", $initialDate));
                break;
        }
        Yii::warning($nextHour, '$nextHour');
        return ['isNextHour' => $isNextHour, 'date' => date("Y-m-d $nextHour:$nextMinute:00")];
    }

    private static function getNextHour($initialDate, $isNext, $hourTypeId, $hourProps = null)
    {
        $isNextDay = false;
        $initialDate = strtotime($initialDate);
        Yii::warning($initialDate, '$initialDate142');
        $nextHour = date('H', $initialDate);
        switch ($hourTypeId) {
            case 1:
                if($isNext) {
                    $nextHour = date('H', strtotime('+1 hour', $initialDate));
                    if(date('d', $initialDate) != date('d', strtotime("+1 hour", $initialDate))) {
                        $isNextDay = true;
                    }
                }
                break;
            case 2:
                if($isNext) {
                    $nextHour = date('H', strtotime("+$hourProps hour", $initialDate));
                    if (date('d', $initialDate) != date('d', strtotime("+$hourProps hour", $initialDate))) {
                        $isNextDay = true;
                    }
                }
                break;
            case 3:
                $arrHours = explode(',', $hourProps);
                if($isNext || !in_array(date('H', $initialDate), $arrHours)) {
                    $nextHour = max($arrHours);
                    if (max($arrHours) < date('H', $initialDate)) {
                        $isNextDay = true;
                        $nextHour = min($arrHours);
                    } else {
                        $arr = array_filter($arrHours, function ($value) use ($initialDate) {
                            return ($value > date('H', $initialDate));
                        });
                        $nextHour = min($arr);
                    }
                }
                break;
        }
        return ['isNextDay' => $isNextDay, 'date' => date("Y-m-d $nextHour:i:00", $initialDate)];
    }

    private static function getNextDay($initialDate, $isNext, $dayTypeId, $dayProps = null)
    {
        $isNextMonth = false;
        $initialDate = strtotime($initialDate);
        $nextDay = date('d', $initialDate);
        switch ($dayTypeId) {
            case 1:
                if($isNext) {
                    $nextDay = date('d', strtotime('+1 day', $initialDate));
                    if (date('m', $initialDate) != date('m', strtotime("+1 day", $initialDate))) {
                        $isNextMonth = true;
                    }
                }
                break;
            case 2:
                if($isNext) {
                    $nextDay = date('d', strtotime("+$dayProps day", $initialDate));
                    if (date('m', $initialDate) != date('m', strtotime("+$dayProps day", $initialDate))) {
                        $isNextMonth = true;
                    }
                }
                break;
            case 3:
                $arrDays = explode(',', $dayProps);
                if($isNext || !in_array(date('d', $initialDate), $arrHours)) {
                    $nextDay = max($arrDays);
                    if (max($arrDays) < date('d', $initialDate)) {
                        $isNextMonth = true;
                        $nextDay = min($arrDays);
                    } else {
                        $arr = array_filter($arrDays, function ($value) use ($initialDate) {
                            return ($value > date('d', $initialDate));
                        });
                        $nextDay = min($arr);
                    }
                }
                break;
        }
        return ['isNextMonth' => $isNextMonth, 'date' => date("Y-m-$nextDay H:i:00", $initialDate)];
    }

    private static function getNextMonth($initialDate, $isNext, $monthTypeId, $monthProps = null)
    {
        $initialDate = strtotime($initialDate);
        $nextMonth = date('m', $initialDate);;
        $year = date('Y', $initialDate);
        switch ($monthTypeId) {
            case 1:
                if($isNext) {
                    $nextMonth = date('m', strtotime('+1 month', $initialDate));
                    if (date('Y', $initialDate) != date('Y', strtotime("+1 month", $initialDate))) {
                        $year = date('Y', strtotime("+1 month", $initialDate));
                    }
                }
                    break;
            case 2:
                if($isNext) {
                    $nextMonth = date('m', strtotime("+$monthProps month", $initialDate));
                    if (date('Y', $initialDate) != date('Y', strtotime("+$monthProps month", $initialDate))) {
                        $year = date('Y', strtotime("+$monthProps month", $initialDate));
                    }
                }
                break;
            case 3:
                $arrMonth = explode(',', $monthProps);
                if($isNext || !in_array(date('m', $initialDate), $arrHours)) {
                    $nextMonth = max($arrMonth);
                    if (max($arrMonth) < date('m', $initialDate)) {
                        $year = date('Y', strtotime("+1 year", $initialDate));
                        $nextMonth = min($arrMonth);
                    } else {
                        $arr = array_filter($arrMonth, function ($value) use ($initialDate) {
                            return ($value > date('m', $initialDate));
                        });
                        $nextMonth = min($arr);
                    }
                }
                break;
        }
        return date("$year-$nextMonth-d H:i:00", $initialDate);
    }

}
