<?php

namespace wm\admin\models\settings\chatbot;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppSearch represents the model behind the search form of `wm\admin\models\settings\chatbot\App`.
 */
class AppSearch extends App
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'bot_code',
                    'code',
                    'js_method_code',
                    'js_param',
                    'icon_file',
                    'contex_code',
                    'extranet_support',
                    'iframe_popup',
                    'title_ru',
                    'title_en',
                    'iframe',
                    'hash',
                    'hidden',
                    'livechat_support'
                ],
                'safe'
            ],
            [['iframe_height', 'iframe_width'], 'integer'],
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
        $query = App::find();

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
            'iframe_height' => $this->iframe_height,
            'iframe_width' => $this->iframe_width,
        ]);

        $query->andFilterWhere(['like', 'bot_code', $this->bot_code])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'js_method_code', $this->js_method_code])
            ->andFilterWhere(['like', 'js_param', $this->js_param])
            ->andFilterWhere(['like', 'icon_file', $this->icon_file])
            ->andFilterWhere(['like', 'contex_code', $this->contex_code])
            ->andFilterWhere(['like', 'extranet_support', $this->extranet_support])
            ->andFilterWhere(['like', 'iframe_popup', $this->iframe_popup])
            ->andFilterWhere(['like', 'title_ru', $this->title_ru])
            ->andFilterWhere(['like', 'title_en', $this->title_en])
            ->andFilterWhere(['like', 'iframe', $this->iframe])
            ->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'hidden', $this->hidden])
            ->andFilterWhere(['like', 'livechat_support', $this->livechat_support]);

        return $dataProvider;
    }
}
