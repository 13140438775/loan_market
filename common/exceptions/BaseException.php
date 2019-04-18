<?php
/**
 * Base exception.
 */

namespace common\exceptions;

class BaseException extends \Exception {
    const SYSTEM_ERR = 10000;
    const NO_PARAM = 11000;
    const BASIC_ERROR = 12000;
    const ERROR_CONFIG = 14000;
    const SAVE_FAIL = 15000;
    
    public static $reasons
        = [
            self::SYSTEM_ERR => '系统繁忙，请稍后重试',
            self::NO_PARAM => '非法入参，请校验',
            self::BASIC_ERROR => '内部请求异常，请稍后重试',
            self::ERROR_CONFIG => '机构API配置错误',
        ];
    
    public function __construct($code = null, $message = null) {
        $this->code = $code;
        $this->message = $message ? $message : self::getReason($code);
    }
    
    public static function getReason($code) {
        return isset(static::$reasons[$code]) ? static::$reasons[$code] : 'Unknown error code';
    }
}