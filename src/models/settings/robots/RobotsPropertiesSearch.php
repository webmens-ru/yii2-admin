<?php

namespace wm\admin\models\settings\robots;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\robots\RobotsProperties;

/**
 * RobotsPropertiesSearch represents the model behind the search form of `wm\admin\models\RobotsProperties`.
 */
class RobotsPropertiesSearch extends RobotsProperties
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
                [['robot_code', 'system_name', 'name', 'description', 'default'], 'safe'],
                [['is_in', 'type_id', 'required', 'multiple', 'sort'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params, $robotCode = '')
    {
        $query = RobotsProperties::find();

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
            'is_in' => $this->is_in,
            'type_id' => $this->type_id,
            'required' => $this->required,
            'multiple' => $this->multiple,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'robot_code', $this->robot_code])
                ->andFilterWhere(['like', 'system_name', $this->system_name])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'default', $this->default]);
        if ($robotCode) {
            $query->andFilterWhere([
                'robot_code' => $robotCode,
            ]);
        }
        return $dataProvider;
    }
}
