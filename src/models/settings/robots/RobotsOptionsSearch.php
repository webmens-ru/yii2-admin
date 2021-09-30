<?php

namespace wm\admin\models\settings\robots;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\robots\RobotsOptions;

/**
 * RobotsOptionsSearch represents the model behind the search form of `wm\admin\models\RobotsOptions`.
 */
class RobotsOptionsSearch extends RobotsOptions {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['property_name', 'robot_code', 'value', 'name'], 'safe'],
                [['sort'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $robotCode = '', $systemName = '') {
        $query = RobotsOptions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'sort' => $this->sort
        ]);

        $query->andFilterWhere(['like', 'property_name', $this->property_name])
                ->andFilterWhere(['like', 'robot_code', $this->robot_code])
                ->andFilterWhere(['like', 'value', $this->value]);
        if ($robotCode) {
            $query->andFilterWhere([
                'robot_code' => $robotCode,
            ]);
        }
        if ($systemName) {
            $query->andFilterWhere([
                'property_name' => $systemName,
            ]);
        }

        return $dataProvider;
    }

}
