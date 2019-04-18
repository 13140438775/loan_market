<?php
/**
 * 还款计划回调服务
 */

namespace common\services;

use Yii;
use common\models\Orders;
use common\models\RepayPlan;
use yii\db\Transaction;
use common\models\RepayPlanItems;
use common\exceptions\RepayPlanFeedbackException;

class RepayPlanFeedbackService
{

    /**
     * repayPlanFeedback 还款计划回调
     * @date     2019/3/14 11:52
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     * @throws RepayPlanFeedbackException
     */
    public static function repayPlanFeedback($data)
    {
        
        // 事务 判断订单状态 更新数据
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order_sn = $data['order_sn'];
            $order    = Orders::findOne(['order_sn' => $order_sn]);
            if ($order === null) {
                \Yii::error("该订单不存在.订单号是:{$order_sn},抛出异常");
                throw new RepayPlanFeedbackException(RepayPlanFeedbackException::ORDER_NOT_EXIT);
            }
            $repayPlan = RepayPlan::findOne(['order_sn' => $data['order_sn']]);
            if ($repayPlan === null) {
                $repayPlan = new RepayPlan();
            }
            $user_id    = $order->user_id;
            $product_id = $order->product_id;
            $repayPlan->order_sn        = $order_sn;
            $repayPlan->total_amount    = $data['total_amount'];
            $repayPlan->user_id         = $user_id;
            $repayPlan->product_id      = $product_id;
            $repayPlan->total_svc_fee   = $data['total_svc_fee'];
            $repayPlan->received_amount = $data['received_amount'];
            $repayPlan->already_paid    = $data['already_paid'];
            $repayPlan->total_period    = $data['total_period'];
            $repayPlan->finish_period   = $data['finish_period'];
            if (!$repayPlan->save()) {
                $transaction->rollBack();
                \Yii::error("保存还款计划失败,订单号是:{$order_sn},抛出异常");
                throw new RepayPlanFeedbackException(RepayPlanFeedbackException::SAVE_REPAY_PLAN_FAIL);
            }
            $repay_plan_id = $repayPlan->id;
            foreach ($data['repayment_plan'] as $item) {
                $planItem = RepayPlanItems::findOne(['repay_plan_id' => $repayPlan->id, 'period_no' => $item['period_no']]);
                if ($planItem === null) {
                    $planItem = new RepayPlanItems();
                }
                $planItem->user_id         = $user_id;
                $planItem->product_id      = $product_id;
                $planItem->repay_plan_id   = $repay_plan_id;
                $planItem->period_no       = $item['period_no'];
                $planItem->principle       = $item['principle'];
                $planItem->interest        = $item['interest'];
                $planItem->service_fee     = $item['service_fee'];
                $planItem->bill_status     = $item['bill_status'];
                $planItem->total_amount    = $item['total_amount'];
                $planItem->already_paid    = $item['already_paid'];
                $planItem->loan_time       = $item['loan_time'];
                $planItem->due_time        = $item['due_time'];
                $planItem->can_pay_time    = $item['can_pay_time'];
                $planItem->finish_pay_time = $item['finish_pay_time'];
                $planItem->overdue_day     = $item['overdue_day'];
                $planItem->overdue_fee     = $item['overdue_fee'];
                $planItem->period_fee_desc = $item['period_fee_desc'];
                $planItem->pay_type        = $item['pay_type'];
                if (!$planItem->save()) {
                    $transaction->rollBack();
                    \Yii::error("保存还款计划子项失败,订单号是:{$order_sn}.还款计划子项期号是:{$item['period_no']},抛出异常");
                    throw new RepayPlanFeedbackException(RepayPlanFeedbackException::SAVE_REPAY_PLAN_ITEMS_FAIL);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new RepayPlanFeedbackException($e->getCode(), $e->getMessage());
        }
    }
}