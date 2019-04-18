<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HotProduct;

/**
 * HotProductSearch represents the model behind the search form of `common\models\HotProduct`.
 */
class HotProductSearch extends HotProduct
{
    public $product_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'is_enable', 'sort', 'created_at', 'updated_at'], 'integer'],
            ['product_name','safe']
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
        $query = HotProduct::find();

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

        $query = $query->leftJoin('credit_product', 'credit_product.id = hot_product.product_id');

        // grid filtering conditions
        $query->andFilterWhere([
            'credit_product.id' => $this->product_id
        ]);
        $query->andFilterWhere(['like','credit_product.product_name', $this->product_name]);
        $query->orderBy('sort');

        return $dataProvider;
    }
}
