<?php
/**
 * Created by PhpStorm.
 * @author: gaoqiang@likingfit.com
 * @createTime: 2018/10/15 18:34
 */

namespace common\services;


class OrderStatusNoticeService
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
    // 头信息
    const REDIS_PREFIX = "loan_market";

    public $redis;

    public function __construct()
    {
        $this->redis = \Yii::$app->redis;
    }

    // 消息提醒key
    public function getOrderStatusNoticeKey()
    {
        return self::REDIS_PREFIX . "_order_status_notice";
    }

    // 异步发送消息提醒（从左边入队，右边出对），数据入队格式
    public function loopNotice()
    {
        $len = $this->redis->Llen($this->getOrderStatusNoticeKey());
        if(!$len) return;
        $temp = $this->redis->Rpop($this->getOrderStatusNoticeKey());
        $res = json_decode($temp, true);
        if(empty($res) || !isset($res['template_id'])) return;

        switch ($res['template_id']) {
            case self::BINDING_CARD:
                OrderNoticeService::bindingCard($res['user_id'], $res['phone'], $res['user_name'], $res['product_name']);
                break;

            case self::REVIEW_SUCCESS:
                OrderNoticeService::reviewSuccess($res['user_id'], $res['phone'], $res['user_name'], $res['product_name'], $res['amount']);
                break;

            case self::REVIEW_FAIL:
                OrderNoticeService::reviewFail($res['user_id'], $res['phone'], $res['user_name'], $res['product_name']);
                break;

            case self::LOAN_SUCCESS:
                OrderNoticeService::loanSuccess($res['user_id'], $res['phone'], $res['user_name'], $res['product_name'], $res['amount']);
                break;

            case self::LOAN_FAIL:
                OrderNoticeService::loanFail($res['user_id'], $res['phone'], $res['user_name'], $res['product_name'], $res['amount']);
                break;

            case self::REPAY_SUCCESS:
                OrderNoticeService::loanSuccess($res['user_id'], $res['phone'], $res['user_name'], $res['product_name'], $res['amount']);
                break;

            case self::REPAY_FAIL:
                OrderNoticeService::repayFail($res['user_id'], $res['phone'], $res['user_name'], $res['product_name'], $res['amount']);
                break;
        }
    }

}