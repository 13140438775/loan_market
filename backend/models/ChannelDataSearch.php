<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ChannelData;
use yii\data\Pagination;

/**
 * ChannelDataSearch represents the model behind the search form of `common\models\ChannelData`.
 */
class ChannelDataSearch extends ChannelData
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
        $query = ChannelData::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 20 ],
        ]);

        $this->load($params);

        $this->date_begin = $this->date_begin && ($this->date_begin != "默认显示当天数据") ? $this->date_begin: date("Y-m-d");
        $this->date_end = $this->date_end && ($this->date_end != "默认显示当天数据")  ? $this->date_end: date("Y-m-d");
        if(strtotime($this->date_begin) > strtotime($this->date_end) ) {
            $this->date_begin = $this->date_end;
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'channel_id' => $this->channel_id,
        ])->andFilterWhere([
            '>=', 'date', date("Ymd", strtotime($this->date_begin))
        ])->andFilterWhere([
            '<=', 'date', date("Ymd", strtotime($this->date_end))
        ])->orderBy("date DESC");
        return $dataProvider;
    }
}
