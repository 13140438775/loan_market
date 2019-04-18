<?php

/**
 * Orders exception.
 */

namespace common\exceptions;

class OrdersException extends BaseException
{
    const ORDER_BIND_CARD_FAIL          = 50000;
    const ORDER_ERROR                   = 50001;
    const ORDER_FAIL                    = 50002;
    const ORDER_NOT_EXIT                = 50003;
    const ORDER_PRODUCT_CONFIG_NOT_EXIT = 50004;
    const ORDER_STATUS_FAIL             = 50005;
    const ORDER_CONFIRM_FAIL            = 50006;
    const ORDER_SAVE_FAIL               = 50007;

    public static $reasons = [
        self::ORDER_BIND_CARD_FAIL    => "订单绑卡失败",
        self::ORDER_ERROR             => "订单号参数验证失败",
        self::ORDER_FAIL              => "下单异常，请稍后再试",
        self::ORDER_NOT_EXIT          => "订单不存在",
        self::PRODUCT_CONFIG_NOT_EXIT => "订单对应的产品配置不存在",
        self::ORDER_STATUS_FAIL       => "订单状态不正确",
        self::ORDER_CONFIRM_FAIL      => "订单签约失败",
        self::ORDER_SAVE_FAIL         => "订单保存失败",
    ];
}