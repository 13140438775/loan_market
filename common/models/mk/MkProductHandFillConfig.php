<?php

namespace common\models\mk;

use Yii;

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
class MkProductHandFillConfig extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_hand_fill_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'career_type', 'term_id', 'created_at', 'updated_at', 'last_operator_id'], 'required'],
            [['product_id', 'career_type', 'term_id', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['options'], 'string', 'max' => 1000],
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
            'career_type' => '职业类型',
            'options' => '如果是选项 填选中的选项k',
            'term_id' => 'mk_hand_fill_term 主键id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_operator_id' => '上次修改人',
        ];
    }
}
