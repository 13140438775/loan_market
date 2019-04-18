<?php

namespace common\exceptions;

/**
 * 单文件一个异常
 */
class UserAcceptException extends BaseException {

    const INVALID_TYPE = 21000;
    const NO_NAME = 21001;
    const NO_ID_CARD = 21002;
    const NO_USER_BASIC = 21003;
    const ID_CARD_ERROR = 21004;
    const NO_PICTURE = 21005;
    const UPLOAD_FAIL = 21006;
    const NO_PASSWORD = 21007;
    const INVALID_BASIC_FAIL = 21008;
    const ERROR_PASSWORD = 21009;
    const MAX_SIZE = 21010;
    const RIGHT_PHONE = 21011;

    const INVALID_FAIL = 11120007;

    public static $reasons = [
        self::INVALID_TYPE => '类型验证错误',
        self::ID_CARD_ERROR => '请输入18位正确身份证号码',
        self::NO_NAME => '真实姓名不能为空',
        self::NO_ID_CARD => '身份证号不能为空',
        self::NO_USER_BASIC => '没有进行实名认证',
        self::NO_PICTURE => '请上传正确的图片格式',
        self::UPLOAD_FAIL => '证件信息模糊，请重新上传',
        self::INVALID_FAIL => '请上传本人有效证件',
        self::INVALID_BASIC_FAIL => '身份信息不符，请检查后重试',
        self::NO_PASSWORD => '请输入服务商密码',
        self::ERROR_PASSWORD => '请输入正确的服务商密码',
        self::MAX_SIZE => '图片大小不能超过2M',
        self::RIGHT_PHONE => '手机号码少于7位',
    ];
}