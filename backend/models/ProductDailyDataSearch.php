<?php

namespace backend\models;

use common\models\Apps;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductDailyData;

/**
 * ProductDailyDataSearch represents the model behind the search form of `common\models\ProductDailyData`.
 */
class ProductDailyDataSearch extends ProductDailyData
{
    public $product_name;
    public $date_begin;
    public $date_end;
    public $app_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'uv', 'pv', 'app_id', 'date', 'created_at'], 'integer'],
            [['product_name', 'date_begin', 'date_end','app_id'], 'safe']
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
        $query = ProductDailyData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $this->date_begin = $this->date_begin ? $this->date_begin : date('Y-m-d', time());
        $this->date_end = $this->date_end ? $this->date_end : date('Y-m-d');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->app_id){
            $query->andFilterWhere(['app_id' => $this->app_id]);
        }
        $query = $query->leftJoin('credit_product', 'credit_product.id = product_daily_data.product_id');
        // grid filtering conditions
        $query->andFilterWhere([
            '>=', 'product_daily_data.date', date("Ymd", strtotime($this->date_begin))
        ])->andFilterWhere([
            '<=', 'product_daily_data.date', date("Ymd", strtotime($this->date_end))
        ])->andFilterWhere([
            'credit_product.id' => $this->product_id,
            'credit_product.product_name' => $this->product_name,
        ])->groupBy('product_id,app_id')->select(['product_id','uv'=>'sum(uv)','pv'=>'sum(pv)','app_id']);

        return $dataProvider;
    }

}
