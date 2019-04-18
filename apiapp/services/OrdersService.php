<?php

namespace app\services;

use common\models\Orders;
use common\helpers\Helper;
use common\models\Product;
use common\models\RepayPlan;
use common\models\RepayPlanItems;
use common\exceptions\BaseException;
use common\exceptions\OrdersException;
use common\models\ProductApiConfig;

/**
 * 订单数据操作
 * Class OrdersService
 * @package app\services
 */
class OrdersService
{
    use Base;

    //前端映射订单状态
    public static $frontOrderStatus = [
        1 => 'getAllStatus',
        2 => Orders::PENDING,
        3 => Orders::WAITING_LOAN,
        4 => Orders::LOAN_SUCCESS,
        5 => Orders::FINISH_ORDER_STATUS,
    ];

    /**
     * 用户中心订单数据
     * @param $userId
     *
     * @return mixed
     */
    public function getUserCenter($userId)
    {
        $orderInfo = Orders::find()->select('status')
            ->where(['>', 'status', 0])
            ->where(['=', 'user_id', $userId])
            ->asArray()->all();

        $data['pending'] = $data['waiting']= $data['repay'] = $data['finish'] = 0;

        if ($orderInfo) {
            $listByStatus = Helper::mapByKey($orderInfo, 'status');

            //待审核2个状态：待审核和待绑卡
            if (isset($listByStatus[Orders::PENDING])) {
                $data['pending'] += count($listByStatus[Orders::PENDING]);
            }

            //待放款
            if (isset($listByStatus[Orders::WAITING_LOAN])) {
                $data['waiting'] += count($listByStatus[self::WAITING_LOAN]);
            }
            //待还款：已放款+还款中
            if (isset($listByStatus[Orders::REPAYMENT])) {
                $data['repay'] += count($listByStatus[self::REPAYMENT]);
            }
            if (isset($listByStatus[Orders::LOAN_SUCCESS])) {
                $data['repay'] += count($listByStatus[self::LOAN_SUCCESS]);
            }
            //已完成
            foreach (Orders::FINISH_ORDER_STATUS as $finishOrderStatus){
                if (isset($listByStatus[$finishOrderStatus])) {
                    $data['finish'] += count($listByStatus[$finishOrderStatus]);
                }
            }
        }

        return $data;
    }

    /**
     * 是否平台新用户
     * @param $userId
     *
     * @return bool
     */
    public function isNewUser($userId)
    {
        if (Orders::find()->where('user_id=:user_id AND status>=:status', [':user_id' => $userId, ':status' => Orders::LOAN_SUCCESS])->one()) {
            return false;
        }
        return true;
    }

    /**
     * 是否平台老用户
     * @param $userId
     *
     * @return bool
     */
    public function isOldUser($userId)
    {
        if (Orders::find()->where('user_id=:user_id AND status>=:status', [':user_id' => $userId, ':status' => Orders::LOAN_SUCCESS])->one()) {
            return true;
        }
        return false;
    }

    /**
     * 是否首贷用户
     * @param $userId
     * @param $productId
     *
     * @return bool
     */
    public function isFirstLoan($userId, $productId)
    {
        if (Orders::find()->where('user_id=:user_id AND product_id=:product_id and status>=:status', [':user_id' => $userId, ':product_id' => $productId, ':status' => Orders::LOAN_SUCCESS])->one()) {
            return false;
        }
        return true;
    }

    /**
     * 是否复贷用户
     * @param $userId
     * @param $productId
     *
     * @return bool
     */
    public function isSecondLoan($userId, $productId)
    {
        if (Orders::find()->where('user_id=:user_id AND product_id=:product_id and status>=:status', [':user_id' => $userId, ':product_id' => $productId, ':status' => Orders::LOAN_SUCCESS])->one()) {
            return true;
        }
        return false;
    }

    /**
     * orderList 订单列表
     * @Date: 2019-03-12 11:36:30
     * @author   周晓坤<1426801685@qq.com>
     * @param $userId
     * @param $status
     * @param int $page 页数
     * @param int $page_num 一页的订单条数
     * @return array
     * @throws OrdersException
     */
    public static function orderList($userId, $status, $page = 1, $page_num = 20)
    {
        $ordersModel = new Orders();
        $status = self::$frontOrderStatus[$status];
        if( is_string($status) && method_exists($ordersModel, $status)){
            $status = $ordersModel->$status();
        }
        try {
            $order_where = [
                'and',
                ['o.user_id' => $userId],
                ["in", 'o.status', $status]
            ];
            $select_info = [
                "product_id"        => "p.id",
                "show_name"         => "p.show_name",
                "logo_url"          => "p.logo_url",
                "id"                => "o.id",
                "order_sn"          => "o.order_sn",
                "loan_amount"       => "o.loan_amount",
                "loan_term"         => "o.loan_term",
                "term_type"         => "o.term_type",
                "created_at"        => "o.created_at",
                "status"            => "o.status",
                "user_id"           => "o.user_id",
                "confirm_amount"    => "o.confirm_amount",
                "confirm_term"      => "o.confirm_term",
                "confirm_term_type" => "o.confirm_term_type",
            ];
            $orders      = $ordersModel->find()
                ->alias('o')
                ->select($select_info)
                ->leftJoin(Product::tableName() . ' AS p', "p.id  = o.product_id")
                ->where($order_where);
            $total_count = $orders->count();
            $total_page  = ceil($total_count / $page_num);
            $order_list  = $orders->limit($page_num)
                ->offset(($page - 1) * $page_num)
                ->orderBy("o.created_at DESC")
                ->asArray()
                ->all();
            array_walk($order_list, function (&$value) {
                $value['logo_url'] = \Yii::$app->params['oss']['url_prefix'] . $value['logo_url'];
                if ($value['status'] == Orders::WAITING_SIGN) {
                    $value['loan_amount'] = $value['confirm_amount'] / 100;
                    $value['loan_term']   = $value['confirm_term'];
                    $value['term_type']   = $value['confirm_term_type'];
                } else {
                    $value['loan_amount'] = $value['loan_amount'] / 100;
                    $value['loan_term']   = $value['loan_term'];
                    $value['term_type']   = $value['term_type'];
                }
                $value['status']     = Orders::ORDER_STATUS_MAP[$value['status']];
                $value['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
            });
            return [
                'total_count' => $total_count,
                'total_page'  => $total_page,
                'order_list'  => $order_list
            ];

        } catch (BaseException $e) {
            \Yii::error('用户' . \Yii::$app->user->getId() . "获取订单列表失败, error: {$e->getMessage()}");
            throw new OrdersException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * orderDetail 订单详情
     * @date     2019/3/12 12:07
     * @author   周晓坤<1426801685@qq.com>
     * @param $user_id
     * @param $order_sn
     * @return array|\yii\db\ActiveRecord|null
     * @throws OrdersException
     */
    public static function orderDetail($user_id, $order_sn)
    {
        // TODO: 1：订单详情的到账银行卡与费用用途和协议说明这几个字段在哪里取到还是未定义???
        //       2: 订单是待审核状态时详情页面需要有一个弹出框提示(且只弹出提示一次)，需要一个字段来记录是否展示过这个弹出框???
        try {
            $selectInfo = [
                "product_id"        => "mp.id",
                "show_name"         => "mp.show_name",
                "logo_url"          => "mp.logo_url",
                "id"                => "o.id",
                "order_sn"          => "o.order_sn",
                "loan_amount"       => "o.loan_amount",
                "loan_term"         => "o.loan_term",
                "term_type"         => "o.term_type",
                "created_at"        => "o.created_at",
                "status"            => "o.status",
                "user_id"           => "o.user_id",
                "confirm_amount"    => "o.confirm_amount",
                "confirm_term"      => "o.confirm_term",
                "confirm_term_type" => "o.confirm_term_type",
                "received_amount"   => "r.received_amount",
                "total_period"      => "r.total_period",
                "repay_plan_id"     => "r.id",
                "total_svc_fee"     => "r.total_svc_fee",
                "total_amount"      => "r.total_amount",
                "already_paid"      => "r.already_paid",
                "finish_period"     => "r.finish_period",
            ];
            $order      = Orders::find()
                ->alias("o")
                ->select($selectInfo)
                ->leftJoin(Product::tableName() . ' AS mp', " mp.id = o.product_id")
                ->leftJoin(RepayPlan::tableName() . ' AS r', "r.order_sn = o.order_sn")
                ->where(['o.user_id' => $user_id, 'o.order_sn' => $order_sn])
                ->asArray()
                ->one();
            if (count($order)) {
                if ($order['status'] == Orders::WAITING_SIGN) {
                    $order['loan_amount'] = $order['confirm_amount'] / 100;
                    $order['loan_term']   = $order['confirm_term'];
                    $order['term_type']   = $order['confirm_term_type'];
                } else {
                    $order['loan_amount'] = $order['loan_amount'] / 100;
                }
                // 列出该订单还款计划子项
                if (!empty($order['repay_plan_id'])) {
                    $planItems = RepayPlanItems::find()
                        ->where(['repay_plan_id' => $order['repay_plan_id'], 'user_id' => $user_id])
                        ->orderBy('period_no')
                        ->asArray()
                        ->all();
                    if (count($planItems)) {
                        array_walk($planItems, function (&$value) {
                            $value['pay_type']        = RepayPlanItems::$bill_repay_type_map[$value['pay_type']];
                            $value['bill_status']     = RepayPlanItems::$bill_status_set[$value['bill_status']];
                            $value['principle']       = $value['principle'] / 100;
                            $value['interest']        = $value['interest'] / 100;
                            $value['service_fee']     = $value['service_fee'] / 100;
                            $value['total_amount']    = $value['total_amount'] / 100;
                            $value['already_paid']    = $value['already_paid'] / 100;
                            $value['overdue_fee']     = $value['overdue_fee'] / 100;
                            $value['rest_amount']     = $value['total_amount'] - $value['already_paid'];
                            $value['created_at']      = date('Y-m-d H:i:s', $value['created_at']);
                            $value['updated_at']      = date('Y-m-d H:i:s', $value['updated_at']);
                            $value['loan_time']       = date('Y-m-d H:i:s', $value['loan_time']);
                            $value['due_time']        = date('Y-m-d H:i:s', $value['due_time']);
                            $value['can_pay_time']    = date('Y-m-d H:i:s', $value['can_pay_time']);
                            $value['finish_pay_time'] = date('Y-m-d H:i:s', $value['finish_pay_time']);
                        });
                    }
                    $order['repay_plan_items'] = $planItems;
                }
                $order['logo_url']        = \Yii::$app->params['oss']['url_prefix'] . $order['logo_url'];
                $order['status']          = Orders::ORDER_STATUS_MAP[$order['status']];
                $order['total_svc_fee']   = $order['total_svc_fee'] / 100;
                $order['received_amount'] = $order['received_amount'] / 100;
                $order['total_amount']    = $order['total_amount'] / 100;
                $order['already_paid']    = $order['already_paid'] / 100;
                $order['rest_amount']     = $order['total_amount'] - $order['already_paid'];
                $order['rest_period']     = $order['total_period'] - $order['finish_period'];
                $order['created_at']      = date('Y-m-d H:i:s', $order['created_at']);
            }
            return $order;

        } catch (BaseException $e) {
            \Yii::error("用户" . \Yii::$app->user->getId() . "获取订单详情失败, error:{$e->getMessage()}");
            throw new OrdersException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 是否有进行中的订单
     * @param $userId
     * @param $productId
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function inProgressOrder($userId, $productId)
    {
        $where = [
            'and',
            ['user_id' => $userId],
            ['product_id' => $productId],
            ['in', 'status', Orders::IN_PROGRESS],
        ];

        return Orders::find()->where($where)->orderBy('id desc')->asArray()->one();
    }

    /**
     * 生成订单
     * @param $productId
     * @param $userId
     * @param $amount
     * @param $term
     * @param $termType
     * @param $application
     *
     * @return bool
     */
    public function createOrder($productId, $userId, $amount, $term, $termType, $application = 1)
    {
        $ordersModel              = new Orders();
        $ordersModel->product_id  = $productId;
        $ordersModel->user_id     = $userId;
        $ordersModel->loan_amount = $amount;
        $ordersModel->loan_term   = $term;
        $ordersModel->term_type   = $termType;
        $ordersModel->order_sn    = $this->getOrderSn();
        $ordersModel->application = $application;

        if ($ordersModel->save()) {
            return $ordersModel->order_sn;
        }
        return false;
    }

    /**
     * 生成唯一订单号
     * @return string
     */
    public function getOrderSn()
    {
        $uniqid = uniqid();
        $pid    = getmypid();
        $time   = time();

        return md5($pid . $uniqid . $time);
    }

    /**
     * repayPlanDetail 订单还款计划详情
     * @date     2019/3/19 10:59
     * @author   周晓坤<1426801685@qq.com>
     * @param $user_id
     * @param $order_id
     * @param $repay_plan_id
     * @return array|\yii\db\ActiveRecord|null
     * @throws OrdersException
     */
    public static function repayPlanDetail($user_id, $order_id, $repay_plan_id)
    {
        try {
            $order = Orders::findOne($order_id);
            if ($order === null) {
                throw new OrdersException(OrdersException::ORDER_NOT_EXIT);
            }
            $selectInfo = [
                'total_svc_fee', 'received_amount', 'already_paid',
                'total_period', 'finish_period', 'total_amount'
            ];
            $repayPlan  = RepayPlan::find()->where(['id' => $repay_plan_id, 'user_id' => $user_id])
                ->select($selectInfo)->asArray()->one();
            if (count($repayPlan)) {
                $repayPlan['loan_amount'] = $order->loan_amount / 100;
                // 列出该订单还款计划子项
                $selectItem = ['id', 'bill_status', 'period_no', 'overdue_fee', 'already_paid', 'total_amount', 'due_time'];
                $planItems  = RepayPlanItems::find()
                    ->select($selectItem)
                    ->where(['repay_plan_id' => $repay_plan_id, 'user_id' => $user_id])
                    ->orderBy('period_no')
                    ->asArray()
                    ->all();
                if (count($planItems)) {
                    array_walk($planItems, function (&$value) {
                        $value['bill_status']  = RepayPlanItems::$bill_status_set[$value['bill_status']];
                        $value['total_amount'] = $value['total_amount'] / 100;
                        $value['already_paid'] = $value['already_paid'] / 100;
                        $value['overdue_fee']  = $value['overdue_fee'] / 100;
                        $value['rest_amount']  = $value['total_amount'] - $value['already_paid'];
                        $value['due_time']     = date("Y年m月d日", $value['due_time']);

                    });
                }
                $repayPlan['repay_plan_items'] = $planItems;
                $repayPlan['total_svc_fee']    = $repayPlan['total_svc_fee'] / 100;
                $repayPlan['received_amount']  = $repayPlan['received_amount'] / 100;
                $repayPlan['total_amount']     = $repayPlan['total_amount'] / 100;
                $repayPlan['already_paid']     = $repayPlan['already_paid'] / 100;
                $repayPlan['rest_amount']      = $repayPlan['total_amount'] - $repayPlan['already_paid'];
                $repayPlan['rest_period']      = $repayPlan['total_period'] - $repayPlan['finish_period'];
            }
            return $repayPlan;
        } catch (\Exception $e) {
            \Yii::error("用户" . \Yii::$app->user->getId() . "获取订单还款计划详情失败, error:{$e->getMessage()}");
            throw new OrdersException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * repayPlanItemDetail 还款计划子项详情
     * @date     2019/3/19 10:59
     * @author   周晓坤<1426801685@qq.com>
     * @param $user_id
     * @param $repay_plan_item_id
     * @return RepayPlanItems|null
     * @throws OrdersException
     */
    public static function repayPlanItemDetail($user_id, $repay_plan_item_id)
    {
        try {
            $repayPlanItem = RepayPlanItems::findOne(['id' => $repay_plan_item_id, 'user_id' => $user_id]);
            if ($repayPlanItem !== null) {
                $repayPlanItem->pay_type        = RepayPlanItems::$bill_repay_type_map[$repayPlanItem->pay_type];
                $repayPlanItem->bill_status     = RepayPlanItems::$bill_status_set[$repayPlanItem->bill_status];
                $repayPlanItem->principle       = $repayPlanItem->principle / 100;
                $repayPlanItem->interest        = $repayPlanItem->interest / 100;
                $repayPlanItem->service_fee     = $repayPlanItem->service_fee / 100;
                $repayPlanItem->total_amount    = $repayPlanItem->total_amount / 100;
                $repayPlanItem->already_paid    = $repayPlanItem->already_paid / 100;
                $repayPlanItem->overdue_fee     = $repayPlanItem->overdue_fee / 100;
                $repayPlanItem->created_at      = date('Y-m-d H:i:s', $repayPlanItem->created_at);
                $repayPlanItem->updated_at      = date('Y-m-d H:i:s', $repayPlanItem->updated_at);
                $repayPlanItem->loan_time       = date('Y-m-d H:i:s', $repayPlanItem->loan_time);
                $repayPlanItem->due_time        = date('Y-m-d H:i:s', $repayPlanItem->due_time);
                $repayPlanItem->can_pay_time    = date('Y-m-d H:i:s', $repayPlanItem->can_pay_time);
                $repayPlanItem->finish_pay_time = date('Y-m-d H:i:s', $repayPlanItem->finish_pay_time);
            }
            return $repayPlanItem;
        } catch (\Exception $e) {
            \Yii::error("用户" . \Yii::$app->user->getId() . "获取订单还款计划子项详情失败, error:{$e->getMessage()}");
            throw new OrdersException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * toSignUp 订单签约
     * @date     2019/3/21 15:23
     * @author   周晓坤<1426801685@qq.com>
     * @param $user_id
     * @param $order_sn
     * @return bool
     */
    public static function toSignUp($user_id, $order_sn)
    {
        try {
            $order = Orders::findOne(['order_sn' => $order_sn, 'user_id' => $user_id]);
            if ($order === null) {
                throw new OrdersException(OrdersException::ORDER_NOT_EXIT);
            }
            if ($order->status != Orders::WAITING_SIGN) {
                throw new OrdersException(OrdersException::ORDER_STATUS_FAIL);
            }
            $productConfig = ProductApiConfig::findOne(['product_id' => $order->product_id]);
            if ($productConfig === null) {
                throw new OrdersException(OrdersException::ORDER_PRODUCT_CONFIG_NOT_EXIT);
            }
            if ($productConfig->is_h5_sign_page) {
                // 有H5签约
                // TODO: 跳转到对方的url
            }
            // TODO:请求签约接口
            // $res = curl::*********;
            $result = 0;
            if (!$result) {
                throw new OrdersException(OrdersException::ORDER_CONFIRM_FAIL);
            }
            $order->status = Orders::WAITING_LOAN;
            if (!$order->save()) {
                throw new OrdersException(OrdersException::ORDER_SAVE_FAIL);
            }
            return true;
        } catch (\Exception $e) {
            \Yii::error("用户" . $user_id . "签约异常, error:{$e->getMessage()}");
            throw new OrdersException($e->getCode(), $e->getMessage());
        }

    }
}

