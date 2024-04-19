<?php

namespace wm\admin\models\settings\events;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\events\EventsDirectory;

/**
 * EventsDirectorySearch represents the model behind the search form of
 * `wm\admin\models\settings\events\EventsDirectory`.
 */
class EventsDirectorySearch extends EventsDirectory
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
                [['name', 'description', 'category_name'], 'safe'],
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
        $query = EventsDirectory::find();

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
        $query->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'description', $this->description])
        ->andFilterWhere(['like', 'category_name', $this->category_name]);

        return $dataProvider;
    }
}
