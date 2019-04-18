<?php

namespace app\services;


use common\exceptions\OrdersException;
use common\exceptions\ProductException;
use common\exceptions\RepayException;
use common\helpers\Helper;
use common\models\Orders;
use common\models\ProductApiConfig;
use common\models\ProductProperty;
use common\models\RepayPlan;
use common\models\RepayPlanItems;
use common\models\UserBank;
use common\services\CommonMethodsService;

/**
 * 还款
 * Class RepayService
 * @package app\services
 */
class RepayService extends BaseService
{
    /**
     * 文件描述 判断订单是否满足还款的条件
     * Created On 2019-03-20 16:45
     * Created By heyafei
     * @param $repay_plan_id
     * @param $repay_plan_item_id
     * @throws ProductException
     * @throws RepayException
     */
    public static function repayOrders($repay_plan_id, $repay_plan_item_id)
    {
        $repay_plan = RepayPlan::findOne($repay_plan_id);
        $product_property = ProductProperty::findOne(['product_id' => $repay_plan->product_id]);
        // 产品不存在
        if(!$product_property) {
            throw new ProductException(ProductException::NOT_FOUND);
        }

        // 不支持主动还款
        if(!$product_property->can_manual_repay) {
            throw new RepayException(RepayException::NO_MANUAL_REPAY);
        }

        // 单期/多期
        $manual_repay_detail = json_decode($product_property->manual_repay_detail, true); // 主动还款 详情
        if($repay_plan->total_period > 1) {
            self::morePeriods($manual_repay_detail['more'], $repay_plan_item_id);
        } else {
            self::onePeriods($manual_repay_detail['one'], $repay_plan_item_id);
        }
    }


    // 多期
    public static function morePeriods($more, $repay_plan_item_id)
    {
        $repay_plan_items = RepayPlanItems::findOne($repay_plan_item_id);
        // 排除选中的逾期订单
        if(date("Ymd", $repay_plan_items->due_time) < date("Ymd")) {
            return ;
        }

//        // 不合并逾期
//        if($more['overdue_need_combine'] == RepayPlan::NO_COMBINE_OVERDUE) {
//            // 有逾期
//            $orders = self::overdueOrders($repay_plan_items->repay_plan_id);
//            if($orders) {
//                throw new RepayException(RepayException::OVERDUE_ORDERS);
//            }
//            // 无逾期 部分账单和最后一期交给APP端处理不需要调用接口
//        // 合并预期
//        } else {
//            // 多期只可以提前还全款
//            if($more['repayment_mode'] == RepayPlan::MORE_BEFORE_ALL) return ;
//            // 多期可以提前还任意期数
//            if ($more['repayment_mode'] == RepayPlan::MORE_BEFORE_ANY) return ;
//            // 只可以还当前期数
//            if ($more['repayment_mode'] == RepayPlan::MORE_BEFORE_CURRENT) {
//                if($repay_plan_items->bill_status != RepayPlanItems::UNPAID) {
//                    throw new RepayException(RepayException::CURRENT_ORDERS);
//                }
//            }
//        }

        // 1.多期只可以提前还全款
        if($more['repayment_mode'] == RepayPlan::MORE_BEFORE_ALL) {
            // 1.1不合并逾期
            if($more['overdue_need_combine'] == RepayPlan::NO_COMBINE_OVERDUE) {
                // 有逾期
                $orders = self::overdueOrders($repay_plan_items->repay_plan_id);
                if($orders) {
                    throw new RepayException(RepayException::OVERDUE_ORDERS);
                }
            }
            // 1.2部分账单和最后一期交给APP端处理不需要调用接口
        // 2.多期可以提前还任意期数
        } elseif ($more['repayment_mode'] == RepayPlan::MORE_BEFORE_ANY) {
            // 2.1不合并逾期
            if($more['overdue_need_combine'] == RepayPlan::NO_COMBINE_OVERDUE) {
                // 有逾期
                $orders = self::overdueOrders($repay_plan_items->repay_plan_id);
                if($orders) {
                    throw new RepayException(RepayException::OVERDUE_ORDERS);
                }
            }
        // 3.只可以还当前期数
        }elseif ($more['repayment_mode'] == RepayPlan::MORE_BEFORE_CURRENT) {
            // 3.1不合并逾期
            if($more['overdue_need_combine'] == RepayPlan::NO_COMBINE_OVERDUE) {
                // 3.1.1有逾期
                $orders = self::overdueOrders($repay_plan_items->repay_plan_id);
                if($orders) {
                    throw new RepayException(RepayException::OVERDUE_ORDERS);
                } else {
                    // 3.1.2.1只可以还当期
                    if($repay_plan_items->bill_status != RepayPlanItems::UNPAID) {
                        throw new RepayException(RepayException::CURRENT_ORDERS);
                    }
                }
            // 3.2合并逾期
            } else {
                // 3.2.1只可以还当期
                if($repay_plan_items->bill_status != RepayPlanItems::UNPAID) {
                    throw new RepayException(RepayException::CURRENT_ORDERS);
                }
            }
        }
    }

    // 单期
    public static function onePeriods($one, $repay_plan_item_id)
    {
        $repay_plan_items = RepayPlanItems::findOne($repay_plan_item_id);
        // 不支持提前还款mode
        if($one['repayment_mode'] == RepayPlan::ONE_NO_BEFORE) {
            // 未到还款日期不允许还款
            if(date("Ymd", $repay_plan_items->due_time) > date("Ymd")) {
                throw new RepayException(RepayException::ONE_NO_REPAY_DATE);
            }
        }
    }


    // 逾期订单
    public static function overdueOrders($repay_plan_id)
    {
        $where = [
            'and',
            [
                'repay_plan_id' => $repay_plan_id,
                'bill_status' => RepayPlanItems::UNPAID
            ],
            ['>=', 'due_time', time()]
        ];
        return RepayPlanItems::find()->where($where)->asArray()->all();
    }

    /**
     * 文件描述 还款页面接口
     * Created On 2019-03-20 19:12
     * Created By heyafei
     * @param $order_sn
     * @param $repay_periods
     * @param $amount
     * @return array
     * @throws OrdersException
     * @throws ProductException
     */
    public static function repayPage($order_sn, $repay_periods, $amount)
    {
        $orders = Orders::findOne(['order_sn' => $order_sn]);
        if(!$orders) {
            throw new OrdersException(OrdersException::ORDER_NOT_EXIT);
        }
        $user_bank = UserBank::findOne($orders->repay_bank_id);
        $product_api_config = ProductApiConfig::findOne(['product_id' => $orders->product_id]);
        if(!$product_api_config) {
            throw new ProductException(ProductException::NOT_FOUND);
        }
        if($product_api_config->repay_mode == ProductApiConfig::REPAY_MODE_API) {
            $h5_url = "";
        } else {
            $h5_url = self::h5Repay($product_api_config, $orders->product_id, $order_sn);
            $h5_url = $h5_url."&return_url=http://".$_SERVER['HTTP_HOST']."/repay/result";
        }

        return [
            "h5_url" => $h5_url,
            "product_id" => $orders->product_id,
            "order_sn" => $order_sn,
            "amount" => $amount,
            "repay_periods" => $repay_periods,
            "bank_info" => [
                "bank_name" => $user_bank->bank_name,
                "card_number" => substr($user_bank->card_number, -4),
                "bank_icon" => $user_bank->bank_icon
            ]
        ];
    }


    // h5还款
    public static function h5Repay(ProductApiConfig $product_api_config, $product_id, $order_sn)
    {
        $sign = Helper::setSignKey($product_api_config->api_ua, $product_api_config->api_secret, "", "");
        if($product_api_config->repay_mode == ProductApiConfig::REPAY_MODE_H5) {
            $params = [
                "order_sn" => $order_sn,
                "auth_type" => 2, // 认证类型 1:绑卡,2:还款
                "return_url" => $_SERVER['SERVER_NAME']."/repay/result",
            ];
            $res = CommonMethodsService::openApiCurl($product_id, "h5Url", "POST", $params);
            $h5_url = $res['response']['auth_url'];
        } else {
            $h5_url = $product_api_config->repay_h5_url;
        }
        return "{$h5_url}?order_sn={$order_sn}&sign={$sign}";
    }
}

