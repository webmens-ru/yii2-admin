<?php

namespace wm\admin\models\settings\chatbot;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\chatbot\ChatbotTypeDirectory;

/**
 * ChatbotTypeDirectorySearch represents the model behind the search form of
 * `wm\admin\models\settings\chatbot\ChatbotTypeDirectory`.
 */
class ChatbotTypeDirectorySearch extends ChatbotTypeDirectory
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'safe'],
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
        $query = ChatbotTypeDirectory::find();

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
