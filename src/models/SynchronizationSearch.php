<?php

namespace wm\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SynchronizationSearch represents the model behind the search form of `app\models\Synchronization`.
 */
class SynchronizationSearch extends Synchronization
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['id', 'active'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param mixed[] $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Synchronization::find();

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
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
