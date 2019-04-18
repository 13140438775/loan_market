<?php
namespace common\models;

use common\behaviors\OperatorBehavior;
use common\models\mk\MkProductProperty;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ProductProperty extends MkProductProperty {


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
            [
                'class' => OperatorBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_operator_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_operator_id'],
                ],
            ]
        ];
    }

    /************************************* 枚举定义 end **********************************************/

    const IS_NO_SHOW_FEE_TXT = 0;
    const IS_SHOW_FEE_TXT = 1;
    static $is_show_fee_txt_set = [
        self::IS_NO_SHOW_FEE_TXT => '否',
        self::IS_SHOW_FEE_TXT => '是',
    ];

    const IS_NO_SHOW_DESC_ENTRY = 0;
    const IS_SHOW_DESC_ENTRY = 1;
    static $is_show_desc_entry_set = [
        self::IS_NO_SHOW_DESC_ENTRY => '否',
        self::IS_SHOW_DESC_ENTRY => '是',
    ];

    const CAN_NO_MANUAL_REPAY = 0;
    const CAN_MANUAL_REPAY = 1;
    static $can_manual_repay_set = [
        self::CAN_NO_MANUAL_REPAY => '否',
        self::CAN_MANUAL_REPAY => '是',
    ];

    const CAN_NO_OFFLINE_REPAY = 0;
    const CAN_OFFLINE_REPAY = 1;
    static $can_offline_repay_set = [
        self::CAN_NO_OFFLINE_REPAY => '否',
        self::CAN_OFFLINE_REPAY => '是',
    ];

    const MANUAL_REPAYMENT_TYPE_ONE = 1;
    const MANUAL_REPAYMENT_TYPE_MORE = 2;
    static $manual_repayment_set = [
        self::MANUAL_REPAYMENT_TYPE_ONE => '单期',
        self::MANUAL_REPAYMENT_TYPE_MORE => '多期',
    ];

    const MANUAL_REPAYMENT_TYPE_WX = 1;
    const MANUAL_REPAYMENT_TYPE_ALI = 2;
    const MANUAL_REPAYMENT_TYPE_OTHER = 3;
    static $manual_repayment_type_set = [
        self::MANUAL_REPAYMENT_TYPE_WX => '微信',
        self::MANUAL_REPAYMENT_TYPE_ALI => '支付宝',
        self::MANUAL_REPAYMENT_TYPE_OTHER => '其他',
    ];


    /************************************* 枚举定义 end **********************************************/

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'is_show_fee_txt' => 'Is Show Fee Txt',
            'is_show_desc_entry' => 'Is Show Desc Entry',
            'hotline' => 'Hotline',
            'offline_service' => 'Offline Service',
            'robot_url' => 'Robot Url',
            'interest_desc' => 'Interest Desc',
            'repay_type' => 'Repay Type',
            'ahead_repay' => 'Ahead Repay',
            'overdue_desc' => 'Overdue Desc',
            'service_fee_type' => 'Service Fee Type',
            'can_manual_repay' => 'Can Manual Repay',
            'manual_repay_mode' => 'Manual Repay Mode',
            'can_offline_repay' => 'Can Offline Repay',
            'jump_url' => 'Jump Url',
            'is_multiple_app' => 'Is Multiple App',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_operator_id' => 'Last Operator ID',
        ];
    }
}