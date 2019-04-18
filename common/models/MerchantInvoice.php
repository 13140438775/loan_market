<?php
namespace common\models;

use common\models\mk\MkMerchantInvoice;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MerchantInvoice extends MkMerchantInvoice
{

    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    static $STATUS_MAP = [
        self::STATUS_VALID => '有效',
        self::STATUS_INVALID => '无效',
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