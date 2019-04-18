<?php

namespace common\exceptions;

/**
 * 单文件一个异常
 */
class ProductException extends BaseException {

    const INVALID_VIEW = 40000;
    const INVALID_PUSH_ADD = 40001;
    const INVALID_USER = 40002;
    const NOT_FOUND = 40003;
    const INVALID_CARD = 40004;
    const HAVE_ORDER = 40005;
    const NOT_LOGIN = 40006;
    const EXIST_ORDER = 40007;

    public static $reasons = [
        self::INVALID_VIEW => '额度获取失败',
        self::INVALID_PUSH_ADD => '请按顺序进行验证',
        self::INVALID_USER => '资质不符',
        self::NOT_FOUND => '该产品不存在',
        self::INVALID_CARD => '身份未验证',
        self::HAVE_ORDER => '已有订单',
        self::NOT_LOGIN => '用户未登录',
        self::EXIST_ORDER => '订单已存在',
    ];
}