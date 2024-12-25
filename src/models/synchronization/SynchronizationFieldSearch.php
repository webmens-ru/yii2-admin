<?php

namespace wm\admin\models\synchronization;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SynchronizationFieldSearch represents the model behind the search form of `app\models\SynchronizationField`.
 */
class SynchronizationFieldSearch extends SynchronizationField
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['id', 'synchronizationEntityId'], 'integer'],
            [['name', 'title'], 'safe'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = SynchronizationField::find();

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
            'synchronizationEntityId' => $this->synchronizationEntityId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
