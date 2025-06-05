<?php

namespace wm\admin\models\settings\biconnectors;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wm\admin\models\settings\biconnectors\BiconnectorSettings;

/**
 * BiconnectorSettingsSearch represents the model behind the search form of `wm\admin\models\settings\biconnectors\BiconnectorSettings`.
 */
class BiconnectorSettingsSearch extends BiconnectorSettings
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['biconnectorId'], 'integer'],
            [['name', 'type', 'code'], 'safe'],
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
     * @param int|null $biconnectorId
     *
     * @return ActiveDataProvider
     */
    public function search($params, $biconnectorId = null)
    {
        $query = BiconnectorSettings::find();

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
            'biconnectorId' => $this->biconnectorId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'code', $this->code]);
        if ($biconnectorId) {
            $query->andFilterWhere([
                'biconnectorId' => $biconnectorId,
            ]);
        }
        return $dataProvider;
    }
}
