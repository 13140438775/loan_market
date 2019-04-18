<?php

namespace common\models;

use common\models\mk\MkRepayPlanItems;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class RepayPlanItems extends MkRepayPlanItems 
{
    const UNREGISTERED = -1;
    const UNPAID = 0;
    const REPAID = 1;
    static $bill_status_set = [
        self::UNREGISTERED => "未出账",
        self::UNPAID => "未还款",
        self::REPAID => "已还款",
    ];

    // 还款支付方式的类型[0:未还款; 1:主动还款；2:系统扣款；3:支付宝转账; 4:银行转账或其它方式]
    const PAY_TYPE_0 = 0;
    const PAY_TYPE_1 = 1;
    const PAY_TYPE_2 = 2;
    const PAY_TYPE_3 = 3;
    const PAY_TYPE_4 = 4;
    static $bill_repay_type_map = [
        self::PAY_TYPE_0 => "未还款",
        self::PAY_TYPE_1 => "主动还款",
        self::PAY_TYPE_2 => "系统扣款",
        self::PAY_TYPE_3 => "支付宝转账",
        self::PAY_TYPE_4 => "银行转账或其它方式",
    ];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}