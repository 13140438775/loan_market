<?php

namespace common\models;

use common\models\mk\MkUserBank;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_user_bank".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $bank_name 银行名称
 * @property string $bank_code 银行编号
 * @property string $card_number 银行卡号
 * @property string $card_phone 银行预留手机号
 * @property int $card_type 银行卡类型 1 信用卡 2 借记卡
 * @property int $use_type 银行卡用途 0:既是收款卡,又是还款卡 1:收款卡 2:还款卡
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class UserBank extends MkUserBank
{
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
