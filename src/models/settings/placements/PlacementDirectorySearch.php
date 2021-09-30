<?php

namespace wm\admin\models\settings\placements;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\placements\PlacementDirectory;

/**
 * PlacementDirectorySearch represents the model behind the search form of `wm\admin\models\settings\placements\PlacementDirectory`.
 */
class PlacementDirectorySearch extends PlacementDirectory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_id', 'description', 'category_name'], 'safe'],
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
        $query = PlacementDirectory::find();

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
        $query->andFilterWhere(['like', 'name_id', $this->name_id])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
