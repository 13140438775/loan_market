<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_merchant_contacts".
 *
 * @property int $id
 * @property int $merchant_id mk_merchant主键id
 * @property string $contacts_name 联系人名字
 * @property string $contacts_phone 联系人电话
 * @property string $email 联系人邮箱
 * @property string $wx 联系人微信
 * @property string $unique_code 唯一码
 * @property int $status 状态 0 删除 1有效
 * @property int $created_at
 * @property int $updated_at
 */
class MkMerchantContacts extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_merchant_contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['contacts_name', 'email', 'wx', 'unique_code'], 'string', 'max' => 50],
            [['contacts_name', 'email', 'wx', 'unique_code'], 'unique'],
            [['contacts_phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'mk_merchant主键id',
            'contacts_name' => '联系人名字',
            'contacts_phone' => '联系人电话',
            'email' => '联系人邮箱',
            'wx' => '联系人微信',
            'unique_code' => '唯一码',
            'status' => '状态 0 删除 1有效',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
