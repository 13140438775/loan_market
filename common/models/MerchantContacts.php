<?php
namespace common\models;

use common\models\mk\MkMerchantContacts;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MerchantContacts extends MkMerchantContacts
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

            ]
        ];
    }

    public function rules()
    {
        return [
            [['merchant_id', 'created_at', 'updated_at'], 'integer'],
            [['contacts_name', 'email', 'wx', 'unique_code'], 'string', 'max' => 50],
            [['contacts_phone'], 'string', 'max' => 15],
            ['status','default','value' => 1],
        ];
    }

}