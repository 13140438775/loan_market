<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ChannelConfig;

/**
 * ChannelConfigSearch represents the model behind the search form of `\common\models\ChannelConfig`.
 */
class ChannelConfigSearch extends ChannelConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'channel_id', 'platform_type', 'package_id', 'cooperate_mode', 'is_general_package', 'is_show_loan_user', 'show_day', 'delivery_terminal', 'h5_template_id', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['channel_name', 'unsign_in_begin_version', 'unsign_in_end_version', 'sign_in_begin_version', 'sign_in_end_version'], 'safe'],
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
        $query = ChannelConfig::find();

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
            'channel_id' => $this->channel_id,
            'platform_type' => $this->platform_type,
            'package_id' => $this->package_id,
            'cooperate_mode' => $this->cooperate_mode,
            'is_general_package' => $this->is_general_package,
            'is_show_loan_user' => $this->is_show_loan_user,
            'show_day' => $this->show_day,
            'delivery_terminal' => $this->delivery_terminal,
            'h5_template_id' => $this->h5_template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_operator_id' => $this->last_operator_id,
        ]);

        $query->andFilterWhere(['like', 'channel_name', $this->channel_name])
            ->andFilterWhere(['like', 'unsign_in_begin_version', $this->unsign_in_begin_version])
            ->andFilterWhere(['like', 'unsign_in_end_version', $this->unsign_in_end_version])
            ->andFilterWhere(['like', 'sign_in_begin_version', $this->sign_in_begin_version])
            ->andFilterWhere(['like', 'sign_in_end_version', $this->sign_in_end_version]);

        return $dataProvider;
    }
}
