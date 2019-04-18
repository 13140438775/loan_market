<?php
/**
 * RepayPlanFeedbackException
 * @date     2019/3/14 20:12
 * @author   周晓坤<1426801685@qq.com>
 * ${PARAM_DOC}
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 */

namespace common\exceptions;

class RepayPlanFeedbackException extends BaseException
{
    const ORDER_NOT_EXIT = 70000;
    const SAVE_REPAY_PLAN_FAIL = 70001;
    const SAVE_REPAY_PLAN_ITEMS_FAIL = 70002;

    public static $reasons = [
        self::ORDER_NOT_EXIT => '订单不存在',
        self::SAVE_REPAY_PLAN_FAIL => '保存订单还款计划失败',
        self::SAVE_REPAY_PLAN_ITEMS_FAIL => '保存还款计划子项失败'
    ];
}