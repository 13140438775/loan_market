<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'sort_min_loan_time', 'sort_min_loan_time_type', 'max_amount', 'min_amount', 'interest_day', 'interest_pay_type', 'show_tag_id', 'created_at', 'updated_at', 'call_type', 'product_type', 'is_fixed_step', 'incr_step', 'incr_amount_step', 'is_same_interest', 'term_type', 'min_term', 'max_term', 'single_interest', 'single_fee', 'last_operator_id', 'weight', 'filter_user_enable', 'enable_mobile_black', 'min_age', 'max_age', 'filter_net_time', 'online_scenario', 'visible', 'visible_mobile', 'enable_count_limit', 'is_time_sharing', 'limit_begin_time', 'limit_end_time', 'uv_day_limit', 'is_diff_first', 'is_diff_plat', 'first_loan_one_push_limit', 'first_loan_approval_limit', 'second_loan_one_push_limit', 'second_loan_approval_limit', 'config_status', 'display_status'], 'integer'],
            [['name', 'show_name', 'logo_url', 'description', 'show_min_loan_time', 'show_interest_desc', 'show_amount_range', 'show_avg_term', 'interest_pay_type_desc', 'area_filter'], 'safe'],
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
        $query = Product::find();

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
            'merchant_id' => $this->merchant_id,
            'sort_min_loan_time' => $this->sort_min_loan_time,
            'sort_min_loan_time_type' => $this->sort_min_loan_time_type,
            'max_amount' => $this->max_amount,
            'min_amount' => $this->min_amount,
            'interest_day' => $this->interest_day,
            'interest_pay_type' => $this->interest_pay_type,
            'show_tag_id' => $this->show_tag_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'call_type' => $this->call_type,
            'product_type' => $this->product_type,
            'is_fixed_step' => $this->is_fixed_step,
            'incr_step' => $this->incr_step,
            'incr_amount_step' => $this->incr_amount_step,
            'is_same_interest' => $this->is_same_interest,
            'term_type' => $this->term_type,
            'min_term' => $this->min_term,
            'max_term' => $this->max_term,
            'single_interest' => $this->single_interest,
            'single_fee' => $this->single_fee,
            'last_operator_id' => $this->last_operator_id,
            'weight' => $this->weight,
            'filter_user_enable' => $this->filter_user_enable,
            'enable_mobile_black' => $this->enable_mobile_black,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'filter_net_time' => $this->filter_net_time,
            'online_scenario' => $this->online_scenario,
            'visible' => $this->visible,
            'visible_mobile' => $this->visible_mobile,
            'enable_count_limit' => $this->enable_count_limit,
            'is_time_sharing' => $this->is_time_sharing,
            'limit_begin_time' => $this->limit_begin_time,
            'limit_end_time' => $this->limit_end_time,
            'uv_day_limit' => $this->uv_day_limit,
            'is_diff_first' => $this->is_diff_first,
            'is_diff_plat' => $this->is_diff_plat,
            'first_loan_one_push_limit' => $this->first_loan_one_push_limit,
            'first_loan_approval_limit' => $this->first_loan_approval_limit,
            'second_loan_one_push_limit' => $this->second_loan_one_push_limit,
            'second_loan_approval_limit' => $this->second_loan_approval_limit,
            'config_status' => $this->config_status,
            'display_status' => $this->display_status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'show_name', $this->show_name])
            ->andFilterWhere(['like', 'logo_url', $this->logo_url])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'show_min_loan_time', $this->show_min_loan_time])
            ->andFilterWhere(['like', 'show_interest_desc', $this->show_interest_desc])
            ->andFilterWhere(['like', 'show_amount_range', $this->show_amount_range])
            ->andFilterWhere(['like', 'show_avg_term', $this->show_avg_term])
            ->andFilterWhere(['like', 'interest_pay_type_desc', $this->interest_pay_type_desc])
            ->andFilterWhere(['like', 'area_filter', $this->area_filter]);

        return $dataProvider;
    }
}
