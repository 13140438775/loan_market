<?php 

/**
 * Orders exception.
 */

namespace common\exceptions;

class RepayException extends BaseException
{
    const NO_MANUAL_REPAY = 80000;
    const ONE_NO_REPAY_DATE = 80001;
    const OVERDUE_ORDERS = 80002;
    const CURRENT_ORDERS = 80003;

    public static $reasons = [
        self::NO_MANUAL_REPAY => "不支持主动还款",
        self::ONE_NO_REPAY_DATE => "未到还款日期暂不可还款",
        self::OVERDUE_ORDERS => "请先结清逾期才能还非逾期的期数",
        self::CURRENT_ORDERS => "只可提前还当期",
    ];
}