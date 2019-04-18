<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_repay_plan_items".
 *
 * @property int $id 还款计划子项表ID
 * @property int $user_id 用户id
 * @property int $product_id 产品id mk_product 主键id
 * @property int $repay_plan_id 还款计划表
 * @property string $period_no 还款期号
 * @property int $principle 本期还款本金（分）
 * @property int $interest 本期还款利息（分）
 * @property int $service_fee 本期服务费用（分）
 * @property int $bill_status 本期账单状态[-1:未出账;0:未还款；1:已还款；]
 * @property int $total_amount 本期还款总额（分）
 * @property int $already_paid 本期已还金额（分）
 * @property int $loan_time 实际起息时间
 * @property int $due_time 最迟还款时间(精确到秒超过改时间就算逾期)
 * @property int $can_pay_time 可以还款时间
 * @property int $finish_pay_time 实际完成还款时间
 * @property int $overdue_day 逾期天数
 * @property int $overdue_fee 逾期费用（分）
 * @property string $period_fee_desc 本期费用描述
 * @property int $pay_type 还款支付方式:[0:未还款; 1:主动还款；2:系统扣款；3:支付宝转账; 4:银行转账或其它方式]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MkRepayPlanItems extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_repay_plan_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'repay_plan_id', 'period_no', 'principle', 'interest', 'service_fee', 'bill_status', 'total_amount', 'already_paid', 'loan_time', 'due_time', 'overdue_day', 'overdue_fee', 'period_fee_desc', 'pay_type'], 'required'],
            [['user_id', 'product_id', 'repay_plan_id', 'principle', 'interest', 'service_fee', 'bill_status', 'total_amount', 'already_paid', 'loan_time', 'due_time', 'can_pay_time', 'finish_pay_time', 'overdue_day', 'overdue_fee', 'pay_type', 'created_at', 'updated_at'], 'integer'],
            [['period_no'], 'string', 'max' => 32],
            [['period_fee_desc'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '还款计划子项表ID',
            'user_id' => '用户id',
            'product_id' => '产品id mk_product 主键id',
            'repay_plan_id' => '还款计划表',
            'period_no' => '还款期号',
            'principle' => '本期还款本金（分）',
            'interest' => '本期还款利息（分）',
            'service_fee' => '本期服务费用（分）',
            'bill_status' => '本期账单状态[-1:未出账;0:未还款；1:已还款；]',
            'total_amount' => '本期还款总额（分）',
            'already_paid' => '本期已还金额（分）',
            'loan_time' => '实际起息时间',
            'due_time' => '最迟还款时间(精确到秒超过改时间就算逾期)',
            'can_pay_time' => '可以还款时间',
            'finish_pay_time' => '实际完成还款时间',
            'overdue_day' => '逾期天数',
            'overdue_fee' => '逾期费用（分）',
            'period_fee_desc' => '本期费用描述',
            'pay_type' => '还款支付方式:[0:未还款; 1:主动还款；2:系统扣款；3:支付宝转账; 4:银行转账或其它方式]',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
