<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CreditProduct;

/**
 * CreditProductSearch represents the model behind the search form of `common\models\CreditProduct`.
 */
class CreditProductSearch extends CreditProduct
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_phone', 'product_type', 'up_time', 'product_status', 'min_credit', 'max_credit', 'rate_type', 'rate_num', 'min_credit_days', 'max_credit_days', 'credit_limit_type', 'avg_credit_days', 'avg_credit_limit_type', 'fast_loan', 'fast_loan_type', 'credit_base', 'tag_id', 'uv_limit', 'sort', 'is_inner', 'is_valid', 'created_at', 'updated_at'], 'integer'],
            [['product_name', 'product_qq', 'product_features', 'product_desc', 'apply_conditions', 'url', 'logo_url', 'apply_materia', 'tag_ids'], 'safe'],
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
        $query = CreditProduct::find();

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
            'product_phone' => $this->product_phone,
            'product_type' => $this->product_type,
            'up_time' => $this->up_time,
            'product_status' => $this->product_status ? $this->product_status: "",
            'min_credit' => $this->min_credit,
            'max_credit' => $this->max_credit,
            'rate_type' => $this->rate_type,
            'rate_num' => $this->rate_num,
            'min_credit_days' => $this->min_credit_days,
            'max_credit_days' => $this->max_credit_days,
            'credit_limit_type' => $this->credit_limit_type,
            'avg_credit_days' => $this->avg_credit_days,
            'avg_credit_limit_type' => $this->avg_credit_limit_type,
            'fast_loan' => $this->fast_loan,
            'fast_loan_type' => $this->fast_loan_type,
            'credit_base' => $this->credit_base,
            'tag_id' => $this->tag_id,
            'uv_limit' => $this->uv_limit,
            'sort' => $this->sort,
            'is_inner' => $this->is_inner,
            'is_valid' => $this->is_valid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'product_qq', $this->product_qq])
            ->andFilterWhere(['like', 'product_features', $this->product_features])
            ->andFilterWhere(['like', 'product_desc', $this->product_desc])
            ->andFilterWhere(['like', 'apply_conditions', $this->apply_conditions])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'logo_url', $this->logo_url])
            ->andFilterWhere(['like', 'apply_materia', $this->apply_materia]);
        $query->orderBy('product_status ASC, sort ASC, up_time ASC, updated_at DESC');

        return $dataProvider;
    }
}
