<?php

namespace wm\admin\models\settings\biconnectors;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BiconnectorTablesSearch represents the model behind the search form of `wm\admin\models\settings\biconnectors\BiconnectorTokens`.
 */
class BiconnectorTokensSearch extends BiconnectorTokens
{
    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['id','token'], 'safe'],
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
        $query = BiconnectorTokens::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
