<?php

namespace wm\admin\models\settings\biconnectors;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\biconnectors\Biconnector;

/**
 * BiconnectorSearch represents the model behind the search form of `wm\admin\models\settings\biconnectors\Biconnector`.
 */
class BiconnectorSearch extends Biconnector
{

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['id', 'sort', 'bx24Id', 'isSystem'], 'integer'],
            [['title', 'logo', 'description', 'urlCheck', 'urlTableList', 'urlTableDescription', 'urlData'], 'safe'],
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
        $query = Biconnector::find();

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
            'sort' => $this->sort,
            'bx24Id' => $this->bx24Id,
            'isSystem' => $this->isSystem,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'urlCheck', $this->urlCheck])
            ->andFilterWhere(['like', 'urlTableList', $this->urlTableList])
            ->andFilterWhere(['like', 'urlTableDescription', $this->urlTableDescription])
            ->andFilterWhere(['like', 'urlData', $this->urlData]);

        return $dataProvider;
    }
}
