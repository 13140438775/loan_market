<?php

namespace common\models;

use common\models\mk\MkOrders;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Product;

class Orders extends MkOrders
{
    CONST UN_FINISH_PUSH = 0;
    CONST PENDING        = 1;
    CONST PENDING_FAIL   = 2;
    CONST TIED_CARD      = 3;
    CONST WAITING_LOAN   = 4;
    CONST LOAN_FAIL      = 5;
    CONST LOAN_SUCCESS   = 6;
    CONST FINISH         = 7;
    CONST REPAYMENT      = 8;
    CONST LONG_TIME      = 9;
    CONST WAITING_SIGN   = 10;

    const ORDER_STATUS_MAP = [
        self::UN_FINISH_PUSH => '推单中',
        self::PENDING        => '待审核',
        self::PENDING_FAIL   => '审核失败',
        self::TIED_CARD      => '待绑卡',
        self::WAITING_LOAN   => '待放款',
        self::LOAN_FAIL      => '放款失败',
        self::LOAN_SUCCESS   => '放款成功', //（未还款状态：包含剩XX天还款/已逾期XX天）
        self::FINISH         => '已还款',
        self::REPAYMENT      => '还款中',
        self::LONG_TIME      => '审核超时',
        self::WAITING_SIGN   => '待签约',
    ];
    //已结束订单集合
    CONST FINISH_ORDER_STATUS = [
        self::PENDING_FAIL,
        self::LOAN_FAIL,
        self::FINISH,
        self::LONG_TIME,
    ];
    //放款成功用户
    CONST LOAN_SUCCESS_LIST = [
        self::LOAN_SUCCESS,
        self::FINISH,
        self::REPAYMENT
    ];
    //进行中的订单
    CONST IN_PROGRESS = [
        self::UN_FINISH_PUSH,
        self::PENDING,
        self::TIED_CARD,
        self::WAITING_LOAN,
        self::LOAN_SUCCESS,
        self::REPAYMENT,
        self::WAITING_SIGN
    ];
    // 待还款的订单
    CONST NO_REPAY = [
        self::LOAN_SUCCESS,
        self::REPAYMENT
    ];
    /**
     * 获取全部订单状态
     * @return array
     */
    public function getAllStatus(){
        return array_keys(self::ORDER_STATUS_MAP);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

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