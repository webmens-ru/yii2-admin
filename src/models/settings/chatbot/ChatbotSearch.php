<?php

namespace wm\admin\models\settings\chatbot;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\chatbot\Chatbot;

/**
 * ChatbotSearch represents the model behind the search form of `wm\admin\models\settings\chatbot\Chatbot`.
 */
class ChatbotSearch extends Chatbot
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'type_id', 'p_name', 'p_last_name', 'p_color_name', 'p_email', 'p_personal_birthday', 'p_work_position', 'p_personal_www', 'p_personal_photo_url', 'event_handler', 'event_massege_add', 'event_massege_update', 'event_massege_delete', 'event_welcome_massege', 'event_bot_delete'], 'safe'],
            [['openline', 'p_personal_gender'], 'integer'],
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
        $query = Chatbot::find();

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
            'openline' => $this->openline,
            'p_personal_birthday' => $this->p_personal_birthday,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'type_id', $this->type_id])
            ->andFilterWhere(['like', 'p_name', $this->p_name])
            ->andFilterWhere(['like', 'p_personal_gender', $this->p_personal_gender])
            ->andFilterWhere(['like', 'p_last_name', $this->p_last_name])
            ->andFilterWhere(['like', 'p_color_name', $this->p_color_name])
            ->andFilterWhere(['like', 'p_email', $this->p_email])
            ->andFilterWhere(['like', 'p_work_position', $this->p_work_position])
            ->andFilterWhere(['like', 'p_personal_www', $this->p_personal_www])
            ->andFilterWhere(['like', 'p_personal_photo_url', $this->p_personal_photo_url])
            ->andFilterWhere(['like', 'event_handler', $this->event_handler])
            ->andFilterWhere(['like', 'event_massege_add', $this->event_massege_add])
            ->andFilterWhere(['like', 'event_massege_update', $this->event_massege_update])
            ->andFilterWhere(['like', 'event_massege_delete', $this->event_massege_delete])
            ->andFilterWhere(['like', 'event_welcome_massege', $this->event_welcome_massege])
            ->andFilterWhere(['like', 'event_bot_delete', $this->event_bot_delete]);

        return $dataProvider;
    }
}
