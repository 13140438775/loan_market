<?php
/**
 * Created by PhpStorm.
 * @author: gaoqiang@likingfit.com
 * @createTime: 2018/10/15 18:34
 */

namespace common\services;


class OrderNoticeService
{
    // 待绑卡
    const BINDING_CARD = 105;
    // 审核成功
    const REVIEW_SUCCESS = 106;
    // 审核失败
    const REVIEW_FAIL = 107;
    // 放款成功
    const LOAN_SUCCESS = 108;
    // 放款失败
    const LOAN_FAIL = 109;
    // 还款失败
    const REPAY_FAIL = 115;
    // 已还款
    const REPAY_SUCCESS = 121;


    // 还款提醒（支持主动还款）
    // 1.1用户距离还款T-3日短信提醒
    const REPAY_T_3 = 110;
    // 1.2用户距离还款T-1日短信提醒
    const REPAY_T_1 = 111;
    // 1.3用户距离还款T日短信提醒
    const REPAY_T = 112;
    // 1.4用户距离还款T+1日短信提醒
    const REPAY_T1 = 113;
    // 1.5用户距离还款T+3日短信提醒
    const REPAY_T3 = 114;


    // 还款提醒（不支持主动还款）
    // 2.1用户距离还款T-3日短信提醒
    const NO_REPAY_T_3 = 116;
    // 2.2用户距离还款T-1日短信提醒
    const NO_REPAY_T_1 = 117;
    // 2.3用户距离还款T日短信提醒
    const NO_REPAY_T = 118;
    // 2.4用户距离还款T+1日短信提醒
    const NO_REPAY_T1 = 119;
    // 2.5用户距离还款T+3日短信提醒
    const NO_REPAY_T3 = 120;


    static $title = "借款消息";


    // 待绑卡
    public static function bindingCard($user_id, $phone, $user_name, $product_name)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name]);
        CommonMethodsService::javaSmsApiCurl(self::BINDING_CARD, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，您申请的{$product_name}产品尚未绑卡，请尽快前往APP-我的借款里进行绑卡，绑卡完成后才可放款哦！";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 审核成功
    public static function reviewSuccess($user_id, $phone, $user_name, $product_name, $amount)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl(self::REVIEW_SUCCESS, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，恭喜您在{$product_name}产品的借款审核已经通过，借款金额为{$amount}元，请耐心等待放款！";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 审核失败
    public static function reviewFail($user_id, $phone, $user_name, $product_name)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name]);
        CommonMethodsService::javaSmsApiCurl(self::REVIEW_FAIL, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，很抱歉您在{$product_name}产品的借款申请未通过，可立即申请其他借款产品！";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 放款成功
    public static function loanSuccess($user_id, $phone, $user_name, $product_name, $amount)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl(self::LOAN_SUCCESS, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，恭喜您在{$product_name}产品{$amount}元借款已经成功放款，按时还款有助于提升额度！";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 放款失败
    public static function loanFail($user_id, $phone, $user_name, $product_name, $amount)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl(self::LOAN_FAIL, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，很抱歉您在{$product_name}产品{$amount}元借款放款失败，您可以重新发起借款申请！";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 还款失败
    public static function repayFail($user_id, $phone, $user_name, $product_name, $amount)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl(self::REPAY_FAIL, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，您的{$product_name}产品{$amount}元借款还款失败，请重新发起还款。";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    //已还款
    public static function repaySuccess($user_id, $phone, $user_name, $product_name, $amount)
    {
        // 短信发送
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl(self::REPAY_SUCCESS, $params, $phone);

        // push发送
        $msg = "尊敬的{$user_name}，您在{$product_name}产品{$amount}元借款已经成功还款，额度已更新可以继续借款！";
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }



    // 用户距离还款T-3日短信提醒
    public static function Repay_T_3($user_id, $phone, $user_name, $product_name, $amount, $repay_type, $card_number, $bank_name)
    {
        // 短信发送
        $template_id = $repay_type ? self::REPAY_T_3: self::NO_REPAY_T_3;
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl($template_id, $params, $phone);

        // push发送
        if($repay_type) {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款将于3天后到期，需还款{$amount}元，可在APP我的借款里进行还款，避免逾期！";
        } else {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款将于3天后到期，需还款{$amount}元，请确保尾号{$card_number}的{$bank_name}卡内账户余额充足！";
        }
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 用户距离还款T-1日短信提醒
    public static function Repay_T_1($user_id, $phone, $user_name, $product_name, $amount, $repay_type, $card_number, $bank_name)
    {
        // 短信发送
        $template_id = $repay_type ? self::REPAY_T_1: self::NO_REPAY_T_1;
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl($template_id, $params, $phone);

        // push发送
        if($repay_type) {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款将于1天后到期，需还款{$amount}元，可在APP我的借款里进行还款，避免逾期！";
        } else {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款将于1天后到期，需还款{$amount}元，请确保尾号{$card_number}的{$bank_name}卡内账户余额充足！";
        }
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }


    // 用户距离还款T日短信提醒
    public static function Repay_T($user_id, $phone, $user_name, $product_name, $amount, $repay_type, $card_number, $bank_name)
    {
        // 短信发送
        $template_id = $repay_type ? self::REPAY_T: self::NO_REPAY_T;
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl($template_id, $params, $phone);

        // push发送
        if($repay_type) {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款将于今天到期，需还款{$amount}元，可在APP我的借款里进行还款，避免逾期！";
        } else {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款将于今天到期，需还款{$amount}元，请确保尾号{$card_number}的{$bank_name}卡内账户余额充足！";
        }
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 用户距离还款T+1日短信提醒
    public static function Repay_T1($user_id, $phone, $user_name, $product_name, $amount, $repay_type, $card_number, $bank_name)
    {
        // 短信发送
        $template_id = $repay_type ? self::REPAY_T1: self::NO_REPAY_T1;
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl($template_id, $params, $phone);

        // push发送
        if($repay_type) {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款已逾期1天，需还款{$amount}元，可在APP我的借款里进行还款！";
        } else {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款已逾期1天，需还款{$amount}元，请确保尾号{$card_number}的{$bank_name}卡内账户余额充足！";
        }
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }

    // 用户距离还款T+3日短信提醒
    public static function Repay_T3($user_id, $phone, $user_name, $product_name, $amount, $repay_type, $card_number, $bank_name)
    {
        // 短信发送
        $template_id = $repay_type ? self::REPAY_T3: self::NO_REPAY_T3;
        $params = implode(",", [$user_name, $product_name, $amount]);
        CommonMethodsService::javaSmsApiCurl($template_id, $params, $phone);

        // push发送
        if($repay_type) {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款已逾期3天，需还款{$amount}元，可在APP我的借款里进行还款！";
        } else {
            $msg = "尊敬的{$user_name}，您在{$product_name}产品的借款已逾期3天，需还款{$amount}元，请确保尾号{$card_number}的{$bank_name}卡内账户余额充足！";
        }
        CommonMethodsService::javaPushApiCurl($user_id, $msg, self::$title);
    }
}