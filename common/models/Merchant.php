<?php

namespace common\models;

use common\behaviors\OperatorBehavior;
use common\models\mk\MkMerchant;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_merchant".
 *
 * @property int $id 机构表
 * @property string $company_name 公司名称
 * @property string $mark 备注
 * @property string $description 公司简介
 * @property string $company_licence_url 公司牌照url
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $last_operator_id 最近操作人
 */
class Merchant extends MkMerchant
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
            [
                'class' => OperatorBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_operator_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_operator_id'],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name'], 'required'],
            [['created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['company_name', 'mark', 'description', 'company_licence_url'], 'string', 'max' => 255],
        ];
    }

    public function getMerchantContacts(){
        return $this->hasMany(MerchantContacts::class,['merchant_id'=>'id'])->all();
    }

}
