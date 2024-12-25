<?php

namespace wm\admin\models\settings\chatbot;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ChatbotCommandSearch represents the model behind the search form of
 * `wm\admin\models\settings\chatbot\ChatbotCommand`.
 */
class ChatbotCommandSearch extends ChatbotCommand
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [
                [
                    'bot_code',
                    'command',
                    'common',
                    'hidden',
                    'extranet_support',
                    'title_ru',
                    'params_ru',
                    'title_en',
                    'params_en',
                    'event_command_add'
                ],
                'safe'
            ],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param mixed[] $params
     * @param string $botCode
     *
     * @return ActiveDataProvider
     */
    public function search($params, $botCode = '')
    {
        $query = ChatbotCommand::find();

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
        $query->andFilterWhere(['like', 'bot_code', $this->bot_code])
            ->andFilterWhere(['like', 'command', $this->command])
            ->andFilterWhere(['like', 'common', $this->common])
            ->andFilterWhere(['like', 'hidden', $this->hidden])
            ->andFilterWhere(['like', 'extranet_support', $this->extranet_support])
            ->andFilterWhere(['like', 'title_ru', $this->title_ru])
            ->andFilterWhere(['like', 'params_ru', $this->params_ru])
            ->andFilterWhere(['like', 'title_en', $this->title_en])
            ->andFilterWhere(['like', 'params_en', $this->params_en])
            ->andFilterWhere(['like', 'event_command_add', $this->event_command_add]);
        if ($botCode) {
            $query->andFilterWhere([
                'bot_code' => $botCode,
            ]);
        }

        return $dataProvider;
    }
}
