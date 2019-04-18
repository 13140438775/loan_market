<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductTag;

/**
 * ProductTagSearch represents the model behind the search form of `common\models\ProductTag`.
 */
class ProductTagSearch extends ProductTag
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sort', 'is_enable', 'is_valid', 'created_at', 'updated_at'], 'integer'],
            [['tag_name', 'tag_icon', 'tag_img', 'tag'], 'safe'],
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
        $query = ProductTag::find();

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
            'is_enable' => $this->is_enable,
            'is_valid' => $this->is_valid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query = $query->andFilterWhere(['like', 'tag_name', $this->tag_name])
            ->andFilterWhere(['like', 'tag_icon', $this->tag_icon])
            ->andFilterWhere(['like', 'tag_img', $this->tag_img])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->orderBy("sort");

        return $dataProvider;
    }
}
