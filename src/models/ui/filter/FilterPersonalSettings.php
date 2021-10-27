<?php

namespace wm\admin\models\ui\filter;

use Yii;

class FilterPersonalSettings extends \wm\admin\models\ui\filter\ActiveRecordExtended {

    public static function tableName() {
        return 'admin_filter_personal_settings';
    }

    public function rules() {
        return [
            [['filterId', 'name', 'order', 'fixed'], 'required'],
            [['itemId', 'userId', 'order', 'visible'], 'integer'],[['value'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['filterId'], 'exist', 'skipOnError' => true, 'targetClass' => FilterItem::className(), 'targetAttribute' => ['itemId' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'filterId' => 'Filter ID',
            'order' => 'Позиция',
            'name' => 'Название фильтра',
            'fixed' => 'Фиксированный',
        ];
    }

    public function getFilters() {
        return $this->hasOne(Filter::className(), ['id' => 'filteId']);
    }

}
