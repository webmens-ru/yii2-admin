<?php

namespace wm\admin\models;

use yii\data\ActiveDataProvider;

trait TraitSearch {

    public function search($params) {
        $query = parent::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        foreach ($this->rules()[0][0] as $value) {
            $query->andFilterCompare($value, $this->{$value});
        }
        return $dataProvider;
    }

    public function rules() {
        return [[array_keys($this->attributeLabels()), 'string']];
    }

}
