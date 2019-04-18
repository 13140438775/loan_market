<?php
return [

    'appList' => [
        //校验姓名和身份证二要素信息
        'authIdCard' => 'verify/identity_card',
        //证件扫描
        'ocrIdCard' => 'verify/ocridcard',
        //证件扫描
        'faceid' => 'verify/faceid',
        //校验密码
        'captcha' => 'operator/captcha',
        //校验验证码
        'verify' => 'operator/verify',
        //重置密码第一步, 获取运营商动态密码
        'passwordCaptcha' => 'operator/password/captcha',
        //重置密码第二步, 重置密码
        'passwordReset' => 'operator/password/reset',
        //其他信息添加
        'addInfo' => 'verify/write/info',
        //紧急联系人
        'contact' => 'urgency/contact',
        //运营商报告
        'report' => 'operator/report',
        //用户黑名单
        'userBlackList' => 'user/blacklist',
        // 用户注册
        'register' => 'user/register',
        // 登陆日志
        'login' => 'log/login',

        // 发送短信
        'sms' => 'notice/send/singleSms',
        'push' => 'notice/singlePush',

        // 绑定银行卡
        "bindCard" => 'user/bankcard',
        "cardBin" => 'verify/cardBin',

        // 上传本地通话记录
        "callHistory" => "upload/call/history",

        // 上传手机通讯录
        "addressBook" => "address_book/list",

        // 上传app列表
        "appList"     => "upload/app",

        // 上传设备信息
        "deviceInfo"  => "upload/device",
    ],
    'javaApi' => 'http://47.96.163.186:22001/',
    'javaApiSecond' => 'http://47.96.163.186:22000/',
    'javaApiThird' => 'http://47.110.32.191:22002/',

    'openList' => [
        'getBankList' => 'BindCard.getValidBankList',
        'applyBindCard' => 'BindCard.applyBindCard',
        'isUserAccept' => 'User.isUserAccept',
        'getContracts' => 'Order.getContracts',
        'loanCalculate' => 'Order.loanCalculate',
        'getRepayPlan' => 'Order.getRepayplan',
        'pushUserBaseInfo' => 'Order.pushUserBaseInfo',
        'applyRepay' => 'Order.applyRepay',
        'h5Url' => 'User.authH5Url',
    ],
];
