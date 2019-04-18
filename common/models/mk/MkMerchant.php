<?php

namespace common\models\mk;

use Yii;

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
class MkMerchant extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_merchant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_name', 'company_licence_url', 'created_at', 'updated_at', 'last_operator_id'], 'required'],
            [['created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['company_name', 'mark', 'description', 'company_licence_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '机构表',
            'company_name' => '公司名称',
            'mark' => '备注',
            'description' => '公司简介',
            'company_licence_url' => '公司牌照url',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_operator_id' => '最近操作人',
        ];
    }
}
