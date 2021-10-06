<?php

namespace wm\admin\models\settings;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\Agents;

/**
 * AgentsSearch represents the model behind the search form of `app\modules\baseapp\models\settings\Agents`.
 */
class AgentsSearch extends Agents
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'period', 'status_id'], 'integer'],
            [['name', 'class', 'method', 'params', 'date_run'], 'safe'],
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
    public function search($params)
    {
        $query = Agents::find();

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
            'id' => $this->id,
            'date_run' => $this->date_run,
            'period' => $this->period,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
