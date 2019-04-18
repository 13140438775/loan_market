<?php

namespace common\models;

use common\behaviors\OperatorBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_product_hand_fill_config".
 *
 * @property int $id 手填项字段表
 * @property int $product_id mk_product 主键id
 * @property int $career_type 职业类型
 * @property string $options 如果是选项 填选中的选项k
 * @property int $term_id mk_hand_fill_term 主键id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $last_operator_id 上次修改人
 */
class ProductHandFillConfig extends \common\models\mk\MkProductHandFillConfig
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'term_id'], 'required'],
            [['product_id', 'term_id'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],

            ],
            [
                'class' => OperatorBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_operator_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_operator_id'],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '手填项字段表',
            'product_id' => 'mk_product 主键id',
            'term_id' => 'mk_hand_fill_term 主键id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_operator_id' => '上次修改人',
        ];
    }
    //获取项目选择的认证项 json
    public static function getSelectedTerms($pid){
        $selected = self::find()->select('term_id,options')->where(['product_id' => $pid])->indexBy('term_id')->asArray()->all();
        foreach ($selected as &$item){
            $item['options'] = $item['options'] ? json_decode($item['options']) : '';
        }
        return $selected;
    }

    /**
     * 获取选中的职业类型
     * getSelectedCareer
     * @date     2019-03-22 20:16
     * @author   Wei Yang<suncode_666@163.com>
     */
    public static function getSelectedCareer($id){
        //职业手填项id
        $career_id = HandFillTerm::find()->select('id')->where(['sort' => 0])->scalar();
        if(!$career_id){
            return [];
        }
        $options = self::find()->where(['term_id'=>$career_id,'product_id'=>$id])->select('options')->scalar();
        if($options){
            return json_decode($options);
        }else{
            return [];
        }
    }
}
