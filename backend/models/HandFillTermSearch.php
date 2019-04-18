<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HandFillTerm;

/**
 * HandFillTermSearch represents the model behind the search form of `common\models\HandFillTerm`.
 */
class HandFillTermSearch extends HandFillTerm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'career_type', 'is_must', 'term_group_id', 'sort'], 'integer'],
            [['term_key', 'term_name', 'options'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = HandFillTerm::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'type' => $this->type,
            'career_type' => $this->career_type,
            'is_must' => $this->is_must,
            'term_group_id' => $this->term_group_id,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'term_key', $this->term_key])
            ->andFilterWhere(['like', 'term_name', $this->term_name])
            ->andFilterWhere(['like', 'options', $this->options]);

        return $dataProvider;
    }
}
