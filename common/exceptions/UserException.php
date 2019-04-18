<?php

namespace common\exceptions;

/**
 * 单文件一个异常
 */
class UserException extends BaseException {
    const INVALID_PHONE = 20000;
    const INVALID_CAPTCHA = 20001;
    const EXPIRED_CAPTCHA = 20002;
    const TOKEN_NOT_EXIST = 20003;
    const LOGIN_EXPIRE = 20004;
    const NOT_FOUND = 20005;
    const INVALID_USER = 20006;
    const NO_ACCESS = 20007;
    const PHONE_FORMAT = 20008;
    const PWD_ERROR = 20009;
    const HAS_USER = 20010;
    const OLD_PWD_ERROR = 20011;
    const USER_STORE_FAIL = 20012;
    const CHECK_CAPTCHA = 20013;
    const EXPIRED_PICTURE_CAPTCHA = 20014;
    const CAPTCHA_MAX_LIMIT = 20015;

    public static $reasons = [
        self::INVALID_PHONE => '无效的手机号码',
        self::INVALID_CAPTCHA => '验证码错误',
        self::EXPIRED_CAPTCHA => '验证码已过期',
        self::TOKEN_NOT_EXIST => 'token不存在',
        self::LOGIN_EXPIRE => '登录态失效，请重新登录',
        self::NOT_FOUND => '请确认手机号',
        self::INVALID_USER => '无效用户',
        self::NO_ACCESS => '用户无权限',
        self::PHONE_FORMAT => '手机号格式有误',
        self::PWD_ERROR => '密码错误',
        self::HAS_USER => '此用户已注册',
        self::OLD_PWD_ERROR => '旧密码不正确',
        self::USER_STORE_FAIL => '保存用户信息失败',
        self::CHECK_CAPTCHA => '请先验证图形验证码',
        self::EXPIRED_PICTURE_CAPTCHA => '图形验证码已过期',
        self::CAPTCHA_MAX_LIMIT => '今天短信获取次数已达到上限!',
    ];
}