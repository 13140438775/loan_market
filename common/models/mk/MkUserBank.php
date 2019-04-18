<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_user_bank".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $bank_name 银行名称
 * @property string $bank_code 银行编号
 * @property string $bank_icon 银行图标
 * @property string $card_number 银行卡号
 * @property string $card_phone 银行预留手机号
 * @property int $card_type 银行卡类型 1 信用卡 2 借记卡
 * @property int $use_type 银行卡用途 0:既是收款卡,又是还款卡 1:收款卡 2:还款卡
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MkUserBank extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_user_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'card_type', 'use_type', 'created_at', 'updated_at'], 'integer'],
            [['bank_name', 'bank_code', 'card_number', 'card_phone'], 'string', 'max' => 256],
            [['bank_icon'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'bank_name' => 'Bank Name',
            'bank_code' => 'Bank Code',
            'bank_icon' => 'Bank Icon',
            'card_number' => 'Card Number',
            'card_phone' => 'Card Phone',
            'card_type' => 'Card Type',
            'use_type' => 'Use Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
