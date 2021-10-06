<?php

namespace wm\admin\models\settings;

use Yii;
use DateTime;

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
class Agents extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'admin_agents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'class', 'method', 'params', 'date_run', 'period', 'status_id'], 'required'],
            [['params'], 'string'],
            [['date_run'], 'safe'],
            [['period', 'status_id'], 'integer'],
            [['name', 'class'], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
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

    public function shedulRun() {
//        $date = new DateTime();
        Yii::warning('shedulRun()');

        $dateTimestamp = date("Y-m-d H:i:s");

        $models = self::find()->where(['<=', 'date_run', $dateTimestamp])->andWhere(['status_id' => 1])->all();
        foreach ($models as $model) {
            try {
                call_user_func(array($model->class, $model->method));
            } catch (\Exception $e) {
                
            }
            $timestamp = strtotime($dateTimestamp)+ $model->period;
            $model->date_run = date("Y-m-d H:i:s", $timestamp);
            $model->save();
        }

//        return [
//            'id' => 'ID',
//            'name' => 'Name',
//            'class' => 'Class',
//            'method' => 'Method',
//            'params' => 'Params',
//            'date_run' => 'Date Run',
//            'period' => 'Period',
//        ];
    }

}
