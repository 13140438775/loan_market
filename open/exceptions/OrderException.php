<?php

namespace open\exceptions;

use Yii;

class OrderException extends BaseException
{
    const ORDER_NOT_EXIT                = 70000;
    const ORDER_STATUS_FAIL             = 70001;
    const USER_NOT_EXIT                 = 70002;
    const PRODUCT_NOT_EXIT              = 70003;
    const SAVE_ORDER_FAIL               = 70004;
    const LENDING_STATUS_FAIL           = 70005;
    const ORDER_AMOUNT_FAIL             = 70006;
    const LOAN_TERM_FAIL                = 70007;
    const TERM_TYPE_FAIL                = 70008;
    const SAVE_USER_BLACK_FAIL          = 70009;
    const APPROVE_STATUS_FAIL           = 70010;
    const PRODUCT_CONFIG_NOT_EXIT       = 70011;
    const PRODUCT_CONFIG_IS_MARKET_FAIL = 70012;

    public static $reasons = [
        self::ORDER_NOT_EXIT                => '订单不存在',
        self::ORDER_STATUS_FAIL             => '订单状态不对',
        self::USER_NOT_EXIT                 => '用户不存在',
        self::PRODUCT_NOT_EXIT              => '产品不存在',
        self::SAVE_ORDER_FAIL               => '保存订单失败',
        self::ORDER_AMOUNT_FAIL             => '订单金额不对',
        self::LOAN_TERM_FAIL                => '借款周期不对',
        self::TERM_TYPE_FAIL                => '订单期限类型不对',
        self::SAVE_USER_BLACK_FAIL          => '用户保存黑名单失败',
        self::LENDING_STATUS_FAIL           => '放款状态不对',
        self::APPROVE_STATUS_FAIL           => '订单审核状态不对',
        self::PRODUCT_CONFIG_NOT_EXIT       => '产品后台配置不存在',
        self::PRODUCT_CONFIG_IS_MARKET_FAIL => '产品后台配置是否有商城错误',
    ];
}