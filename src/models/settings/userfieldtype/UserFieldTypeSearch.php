<?php

namespace wm\admin\models\settings\userfieldtype;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\placements\Placement;

/**
 * UserFieldTypeSearch represents the model behind the search form of `wm\admin\models\settings\placements\UserFieldType`.
 */
class UserFieldTypeSearch extends UserFieldType
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['id', 'optionsHeight'], 'integer'],
            [['userTypeId', 'handler', 'title', 'description'], 'safe'],
        ];
    }

    /**
     * @return mixed
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param mixed $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserFieldType::find();

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
            'optionsHeight' => $this->optionsHeight,
        ]);

        $query->andFilterWhere(['like', 'userTypeId', $this->userTypeId])
            ->andFilterWhere(['like', 'handler', $this->handler])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
