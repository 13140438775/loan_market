<?php
/**
 * Created by PhpStorm.
 * @author: gaoqiang@likingfit.com
 * @createTime: 2018/10/15 18:34
 */

namespace common\services;


use common\models\LoanUsers;
use common\models\Orders;
use common\models\Product;
use common\models\RepayPlan;
use common\models\RepayPlanItems;
use common\models\UserBank;

class RepayPlanNoticeService
{
    static $page_num = 500; // 条数

    // 产品属性
    public static function productInfo($product_id)
    {
        $select = [
            "product_id" => "p.id",
            "product_name" => "p.name",
            "can_manual_repay" => "pp.can_manual_repay" // 0-不支持 1-支持
        ];
        $product_info = Product::find()
            ->alias("p")
            ->select($select)
            ->leftJoin("mk_product_property pp", "pp.product_id = p.id")
            ->filterWhere(["p.id" => $product_id])
            ->asArray()
            ->one();
        return $product_info;
    }

    // 用户属性
    public static function userInfo($user_id)
    {
        return LoanUsers::findOne($user_id);
    }

    // 银行卡属性
    public static function bankInfo($repay_plan_id)
    {
        $model = RepayPlan::findOne($repay_plan_id);
        $orders = Orders::findOne(['order_sn' => $model->order_sn]);
        $order_bank = UserBank::find()->where(['id' => $orders->repay_bank_id])->asArray()->one();
        return $order_bank;
    }


    // 还款提醒
    public static function repayPlanNotice($type)
    {
        $time = strtotime(date("Y-m-d"));
        $begin_time = $end_time = 0;
        if($type == "-3") {
            $begin_time = $time + 3 * 86400;
            $end_time = $time + 4 * 86400;
        } elseif ($type == "-1") {
            $begin_time = $time + 1 * 86400;
            $end_time = $time + 2 * 86400;
        } elseif ($type == "0") {
            $begin_time = $time - 0 * 86400;
            $end_time = $time + 1 * 86400;
        } elseif ($type == "1") {
            $begin_time = $time - 1 * 86400;
            $end_time = $time - 0 * 86400;
        } elseif ($type == "3") {
            $begin_time = $time - 3 * 86400;
            $end_time = $time - 2 * 86400;
        }

        $model  = RepayPlanItems::find()
            ->select("repay_plan_id, user_id, product_id, total_amount, already_paid, due_time")
            ->filterWhere(['in', 'bill_status', [RepayPlanItems::UNREGISTERED, RepayPlanItems::UNPAID]])
            ->andFilterWhere([">=", "due_time", $begin_time])
            ->andFilterWhere(["<", "due_time", $end_time]);

        $page_num = self::$page_num;
        $total_count = $model->count();
        $total_page = ceil($total_count / $page_num);

        for ($page = 1; $page <= $total_page; $page++) {
            $order_plan_items = $model->limit($page_num)
                ->offset(($page - 1) * $page_num)
                ->orderBy("due_time ASC")
                ->asArray()
                ->all();
            foreach($order_plan_items AS $val) {
                $product_info = self::productInfo($val['product_id']);
                $user_info = self::userInfo($val['user_id']);
                $bank_info = self::bankInfo($val['repay_plan_id']);
                $amount = ($val['total_amount'] - $val['already_paid']) / 100;
                $product_name = $product_info['product_name'];
                $repay_type = $product_info['can_manual_repay'];

                $card_number = substr($bank_info['card_number'],-4);
                $bank_name = $bank_info['bank_name'];

                // 用户距离还款T-3日短信提醒
                if($type == "-3") {
                    OrderNoticeService::Repay_T_3($user_info->id, $user_info->user_phone, $user_info->real_name, $product_name, $amount, $repay_type, $card_number, $bank_name);
                } elseif ($type == "-1") {
                    OrderNoticeService::Repay_T_1($user_info->id, $user_info->user_phone, $user_info->real_name, $product_name, $amount, $repay_type, $card_number, $bank_name);
                } elseif ($type == "0") {
                    OrderNoticeService::Repay_T($user_info->id, $user_info->user_phone, $user_info->real_name, $product_name, $amount, $repay_type, $card_number, $bank_name);
                } elseif ($type == "1") {
                    OrderNoticeService::Repay_T1($user_info->id, $user_info->user_phone, $user_info->real_name, $product_name, $amount, $repay_type, $card_number, $bank_name);
                } elseif ($type == "3") {
                    OrderNoticeService::Repay_T3($user_info->id, $user_info->user_phone, $user_info->real_name, $product_name, $amount, $repay_type, $card_number, $bank_name);
                }
            }
        }
    }
}