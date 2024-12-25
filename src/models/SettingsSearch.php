<?php

namespace wm\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SettingsSearch represents the model behind the search form of `app\modules\windows\models\Settings`.
 */
class SettingsSearch extends Settings
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name_id', 'value', 'name'], 'safe'],
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
        $query = Settings::find();

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
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
