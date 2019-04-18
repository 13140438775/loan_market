<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Channels;

/**
 * ChannelsManageSearch represents the model behind the search form of `common\models\Channels`.
 */
class ChannelsManageSearch extends Channels
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'merchant_id', 'type', 'cooperation', 'template_id', 'created_at', 'updated_at', 'created_id'], 'integer'],
            [['channel_name', 'channel_id'], 'safe'],
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
        $query = Channels::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'channel_id' => $this->channel_id,
            'merchant_id' => $this->merchant_id,
            'cooperation' => $this->cooperation,
            'is_filling' => $this->is_filling,
            'is_company_name' => $this->is_company_name,
            'template_id' => $this->template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_id' => $this->created_id,
        ]);

        //排除默认type=0导致无法查找数据
        if($this->type){
            $query->andFilterWhere([
                'type' => $this->type
            ]);
        }

        $query->andFilterWhere(['like', 'channel_name', $this->channel_name])->orderBy("updated_at DESC");

        return $dataProvider;
    }
}
