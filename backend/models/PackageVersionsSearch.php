<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PackageVersions;

/**
 * PackageVersionsSearch represents the model behind the search form of `common\models\PackageVersions`.
 */
class PackageVersionsSearch extends PackageVersions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'package_id', 'type', 'operator_id', 'created_at', 'updated_at'], 'integer'],
            [['version_id', 'url'], 'safe'],
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
        
        $query = PackageVersions::find()->where(['package_id' => $params['id']]);
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
            'package_id' => $this->package_id,
            'type' => $this->type,
            'operator_id' => $this->operator_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'version_id', $this->version_id])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }

}
