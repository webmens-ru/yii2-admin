<?php

namespace wm\admin\models\settings\robots;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\robots\Robots;

/**
 * RobotsSearch represents the model behind the search form of `wm\admin\models\Robots`.
 */
class RobotsSearch extends Robots
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['auth_user_id', 'use_subscription', 'use_placement'], 'integer'],
            [['code', 'handler', 'name'], 'safe'],
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
        $query = Robots::find();

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
            'auth_user_id' => $this->auth_user_id,
            'use_subscription' => $this->use_subscription,
            'use_placement' => $this->use_placement,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'handler', $this->handler])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
