<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "loan_merchantinfo".
 *
 * @property int $id
 * @property string $merchant_name
 * @property string $merchant_desc 商户描述
 * @property string $company_name 主体公司名称
 * @property string $organ_code 组织机构编号
 * @property string $company_short_name 公司简称
 * @property string $company_city 公司城市
 */
class LoanMerchantinfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loan_merchantinfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'merchant_name'], 'required'],
            [['id'], 'integer'],
            [['merchant_name', 'merchant_desc', 'company_name', 'organ_code', 'company_short_name', 'company_city'], 'string', 'max' => 255],
            [['merchant_name'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_name' => '商户',
            'merchant_desc' => 'Merchant Desc',
            'company_name' => 'Company Name',
            'organ_code' => 'Organ Code',
            'company_short_name' => 'Company Short Name',
            'company_city' => 'Company City',
        ];
    }
}
