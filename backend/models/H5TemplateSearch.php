<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\H5Template;

/**
 * H5TemplateSearch represents the model behind the search form of `\common\models\H5Template`.
 */
class H5TemplateSearch extends H5Template
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_show_company_main_body', 'is_show_record_number', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['h5_template_name', 'abbreviation_img', 'banner_img', 'background_color', 'submit_img'], 'safe'],
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
        $query = H5Template::find();

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
            'is_show_company_main_body' => $this->is_show_company_main_body,
            'is_show_record_number' => $this->is_show_record_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_operator_id' => $this->last_operator_id,
        ]);

        $query->andFilterWhere(['like', 'h5_template_name', $this->h5_template_name])
            ->andFilterWhere(['like', 'abbreviation_img', $this->abbreviation_img])
            ->andFilterWhere(['like', 'banner_img', $this->banner_img])
            ->andFilterWhere(['like', 'background_color', $this->background_color])
            ->andFilterWhere(['like', 'submit_img', $this->submit_img]);

        return $dataProvider;
    }
}
