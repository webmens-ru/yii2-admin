<?php

namespace wm\admin\models\settings\chatbot;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\chatbot\ChatbotColorDirectory;

/**
 * ChatbotColorDirectorySearch represents the model behind the search form of `wm\admin\models\settings\chatbot\ChatbotColorDirectory`.
 */
class ChatbotColorDirectorySearch extends ChatbotColorDirectory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'safe'],
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
        $query = ChatbotColorDirectory::find();

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
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
