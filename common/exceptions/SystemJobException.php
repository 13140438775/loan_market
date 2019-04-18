<?php

namespace common\exceptions;

class SystemJobException extends BaseException
{
    const INVALID_TYPE = 22000;
    const USER_PHONE_FAIL = 22001;

    public static $reasons = [
        self::INVALID_TYPE=> '类型验证错误',
        self::USER_PHONE_FAIL => '用户手机号码为空',
    ];
}