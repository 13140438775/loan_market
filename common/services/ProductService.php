<?php

namespace common\services;

use common\models\mk\MkUsersBlack;
use common\models\Orders;
use common\models\Product;
use common\models\RepayPlanItems;

class ProductService
{
    static $product_sink = 0; // 沉底
    static $product_home = 1; // 不变
    static $product_stick = 10000; // 置顶


    // 复贷置顶
    public static function isAgainProduct($user_id, $product_id)
    {
        $where = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'status' => Orders::FINISH
        ];
        $res = Orders::find()->where($where)->one();
        if($res) return self::$product_stick;
        return self::$product_home;
    }

    // 据还款N天
    public static function isRefundProduct($user_id, $product_id)
    {
        $type = "-3";
        $where = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'bill_status' => RepayPlanItems::UNPAID
        ];

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

        $res  = RepayPlanItems::find()
            ->select("repay_plan_id, user_id, product_id, total_amount, already_paid, due_time")
            ->andFilterWhere($where)
            ->andFilterWhere([">=", "due_time", $begin_time])
            ->andFilterWhere(["<", "due_time", $end_time])
            ->asArray()
            ->all();
        if($res) return self::$product_stick;
        return self::$product_home;
    }


    // 在贷产品
    public static function isLoaningProduct($user_id, $product_id)
    {
        $loaning_status = [Orders::LOAN_SUCCESS, Orders::REPAYMENT];
        $where = [
            'and',
            ['user_id' => $user_id, 'product_id' => $product_id],
            ['in', 'status', $loaning_status]
        ];
        $res = Orders::find()->where($where)->one();
        if($res) return self::$product_sink;
        return self::$product_home;
    }

    // TODO 额度为空
    public static function isLimitProduct($user_id, $product_id)
    {
        try{
            $product = Product::findOne($product_id);
            if(!$product) {
                return self::$product_home;
            }

        } catch(\Exception $e) {

        }
        if(!empty($res)) return self::$product_sink;
        return self::$product_home;
    }

    // TODO 资质不符合
    public static function isAptitudeProduct($user_id, $product_id)
    {
        $where = [
            'user_id' => $user_id,
            'product_id' => $product_id
        ];
        $res = MkUsersBlack::find()->where($where)->one();
        if($res) return self::$product_sink;
        return self::$product_home;
    }

    // TODO 不准入
    public static function isAccessProduct($user_id, $product_id)
    {
        $where = [
            'user_id' => $user_id,
            'product_id' => $product_id
        ];
        $res = MkUsersBlack::find()->where($where)->one();
        if($res) return self::$product_sink;
        return self::$product_home;
    }

    // TODO 被拒
    public static function isRefusedProduct($user_id, $product_id)
    {
        $where = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'status' => Orders::PENDING_FAIL
        ];
        $res = Orders::find()->where($where)->one();
        if($res) return self::$product_sink;
        return self::$product_home;
    }

    // 获取所有的产品列表权重
    public static function productList()
    {
        $product_weight = [];
        $product_list = Product::find()->select(["id", "weight"])->orderBy("weight")->asArray()->all();
        foreach($product_list AS $val) {
            $product_weight[$val['id']] = $val['weight'];
        }
        return $product_weight;
    }
}

