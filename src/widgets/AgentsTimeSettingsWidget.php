<?php

namespace wm\admin\widgets;

use yii\base\Widget;
use yii\jui\DatePicker;

/**
 * Class AgentsTimeSettingsWidget
 * @package wm\admin\widgets
 * @property yii\widgets\ActiveForm $form
 */
class AgentsTimeSettingsWidget extends Widget
{
    public $model;
    public $form;
    public function init()
    {
//        parent::init();
//        if ($this->message === null) {
//            $this->message = 'Hello World';
//        }
    }

    public function run()
    {
        return
            $this->form->field($this->model, 'minuteTypeId')
                ->radioList(
                    [
                        1 => 'Каждую минуту',
                        2 => 'Каждую  -ю минуту',
                        3 => 'В указанные минуты (с ведущим нулем)'
                    ]
                )
                ->label('Минуты')
            . $this->form->field($this->model, 'minuteProps')
                ->textInput()
                ->label('Параметры')
            . $this->form->field($this->model, 'hourTypeId')
                ->radioList(
                    [
                        1 => 'Каждый час',
                        2 => 'Каждый  -й час',
                        3 => 'В указанные часы (с ведущим нулем)'
                    ]
                )
                ->label('Часы')
            . $this->form->field($this->model, 'hourProps')
                ->textInput()
                ->label('Параметры')
            . $this->form->field($this->model, 'dayTypeId')
                ->radioList(
                    [
                        1 => 'Каждый день',
                        2 => 'Каждый  -й день',
                        3 => 'В указанные дени (с ведущим нулем)'
                    ]
                )
                ->label('Дни')
            . $this->form->field($this->model, 'dayProps')
                ->textInput()
                ->label('Параметры')
            . $this->form->field($this->model, 'monthTypeId')
                ->radioList(
                    [
                        1 => 'Каждый месяц',
                        2 => 'Каждый  -й месяц',
                        3 => 'В указанные месяцы (с ведущим нулем)'
                    ]
                )
                ->label('Месяцы')
            . $this->form->field($this->model, 'monthProps')
                ->textInput()
                ->label('Параметры')
            . $this->form->field($this->model, 'finishTypeId')
                ->radioList(
                    [
                        1 => 'Нет даты окончания',
                        2 => 'Дата окончания',
                    //                        3 => 'Завершить после "х" повторений'
                    ]
                )
                ->label('Окончание')
            . $this->form->field($this->model, 'finishProps')
                ->widget(DatePicker::className(), ['dateFormat' => 'yyyy-MM-dd',])
                ->label('Параметры');
    }
}
