<?php

namespace wm\admin\models\settings\documentgenerator;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\documentgenerator\Templates;

/**
 * TemplatesSearch represents the model behind the search form of
 * `wm\admin\models\settings\documentgenerator\Templates`.
 */
class TemplatesSearch extends Templates
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'file_path', 'code', 'active', 'with_stamps'], 'safe'],
            [['numerator_id', 'region_id', 'sort'], 'integer'],
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
        $query = Templates::find();

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
            'numerator_id' => $this->numerator_id,
            'region_id' => $this->region_id,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'active', $this->active])
            ->andFilterWhere(['like', 'with_stamps', $this->with_stamps]);

        return $dataProvider;
    }
}
