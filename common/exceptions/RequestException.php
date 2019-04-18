<?php
/**
 * Request exception.
 */

namespace common\exceptions;

class RequestException extends BaseException
{
    const URI_ERR            = 10001;
    const PERMISSION_DENIED  = 10002;
    const INVALID_SIGNATURE  = 10003;
    const INVALID_PARAM      = 10004;
    const REPEAT_REQUEST     = 10005;
    const REQUEST_TIMEOUT    = 10006;
    const UNAUTHORIZED_TOKEN = 10007;
    const REQUEST_TYPE       = 10008;
    const HEADERS_PARAM      = 10009;
    const DATA_SAVE_FAIL     = 10010;
    const VALIDATE_FAIL      = 10011;


    public static $reasons = [
            self::URI_ERR => 'uri not exist',
            self::PERMISSION_DENIED => 'permission denied',
            self::INVALID_SIGNATURE => 'invalid signature',
            self::INVALID_PARAM => 'invalid param',
            self::REPEAT_REQUEST => 'repeated request',
            self::REQUEST_TIMEOUT => 'request timeout',
            self::UNAUTHORIZED_TOKEN => 'unauthorized token',
            self::REQUEST_TYPE   => '请求类型不正确',
            self::HEADERS_PARAM  => 'Headers格式不正确',
            self::DATA_SAVE_FAIL => '数据保存失败',
            self::VALIDATE_FAIL  => '参数验证失败',

    ];
}
