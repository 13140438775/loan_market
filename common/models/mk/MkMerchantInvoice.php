<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_merchant_invoice".
 *
 * @property int $id
 * @property int $merchant_id mk_merchant 表主键id
 * @property string $entry_name 充值登记名称
 * @property string $identification_number 纳税人识别号
 * @property string $bank_name 开户行
 * @property int $card_no 卡号
 * @property string $address 地址
 * @property string $contacts_mode 联系方式 
 * @property string $business_license 营业执照
 * @property string $unique_code 唯一码 根据 充值登记名称、纳税人识别号、银行卡组合判定
 * @property int $status 状态 0无效 1有效
 * @property int $created_at
 * @property int $updated_at
 */
class MkMerchantInvoice extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_merchant_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'card_no', 'status', 'created_at', 'updated_at'], 'integer'],
            [['business_license'], 'string'],
            [['entry_name', 'unique_code'], 'string', 'max' => 50],
            [['identification_number'], 'string', 'max' => 25],
            [['bank_name', 'contacts_mode'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'mk_merchant 表主键id',
            'entry_name' => '充值登记名称',
            'identification_number' => '纳税人识别号',
            'bank_name' => '开户行',
            'card_no' => '卡号',
            'address' => '地址',
            'contacts_mode' => '联系方式 ',
            'business_license' => '营业执照',
            'unique_code' => '唯一码 根据 充值登记名称、纳税人识别号、银行卡组合判定',
            'status' => '状态 0无效 1有效',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
