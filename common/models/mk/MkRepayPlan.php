<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_repay_plan".
 *
 * @property int $id 还款计划表ID
 * @property string $order_sn 唯一订单号
 * @property int $total_amount 还款总额（分）
 * @property int $user_id 用户id
 * @property int $product_id 产品id mk_product 主键id
 * @property int $total_svc_fee 总服务费（分）
 * @property int $received_amount 到账金额（分）
 * @property int $already_paid 已还金额（分）
 * @property int $total_period 总期数
 * @property int $finish_period 已还期数
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MkRepayPlan extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_repay_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_sn', 'total_amount', 'user_id', 'product_id', 'total_svc_fee', 'received_amount', 'already_paid', 'total_period', 'finish_period'], 'required'],
            [['total_amount', 'user_id', 'product_id', 'total_svc_fee', 'received_amount', 'already_paid', 'total_period', 'finish_period', 'created_at', 'updated_at'], 'integer'],
            [['order_sn'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '还款计划表ID',
            'order_sn' => '唯一订单号',
            'total_amount' => '还款总额（分）',
            'user_id' => '用户id',
            'product_id' => '产品id mk_product 主键id',
            'total_svc_fee' => '总服务费（分）',
            'received_amount' => '到账金额（分）',
            'already_paid' => '已还金额（分）',
            'total_period' => '总期数',
            'finish_period' => '已还期数',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
