<?php

namespace common\exceptions;

/**
 * 用户协议弹出框 exception.
 */
class AgreementPopUpException extends BaseException
{
    const APP_ID    = 60000;
    const SAVE_FAIL = 60001;

    public static $reasons = [
        self::APP_ID    => 'app_id不存在',
        self::SAVE_FAIL => '用户协议弹出框保存失败',
    ];
}