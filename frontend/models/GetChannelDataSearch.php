<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LoanUsers;

/**
 * ChannelDataSearch represents the model behind the search form of `common\models\ChannelData`.
 */
class GetChannelDataSearch extends LoanUsers
{
    public $date_begin;
    public $date_end;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['channel_id', 'integer'],
            [['date_begin', 'date_end', 'channel_id'], 'safe'],
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
        $query = LoanUsers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 20 ],
        ]);

        $this->load($params);

        $this->date_begin = $this->date_begin && ($this->date_begin != "默认显示当天数据") ? $this->date_begin: date("Y-m-d");
        $this->date_end = $this->date_end && ($this->date_end != "默认显示当天数据")  ? $this->date_end: date("Y-m-d");
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query = $query->joinWith("channelAssocAccount", true, "INNER JOIN");

        $query->andFilterWhere([
            'channel_id' => $this->channel_id,
        ])->andFilterWhere([
            '>=', 'create_time', date("Y-m-d H:i:s", strtotime($this->date_begin))
        ])->andFilterWhere([
            '<=', 'create_time', date("Y-m-d H:i:s", strtotime($this->date_end) + 24 * 60 * 60)
        ])->groupBy("channel_id");

//        echo $query->createCommand()->getRawSql();die;
        return $dataProvider;
    }
}
