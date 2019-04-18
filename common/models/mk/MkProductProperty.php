<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_property".
 *
 * @property string $id 产品属性
 * @property int $product_id product表主键id
 * @property int $is_show_fee_txt api 息费说明是否展示0是不展示 1 展示
 * @property int $is_show_desc_entry api 产品说明入口是否展示:0是不展示 1 展示
 * @property string $hotline 客服电话
 * @property string $offline_service 线下客服号
 * @property string $robot_url 机器人客服地址
 * @property string $interest_desc 利率说明
 * @property string $repay_type 还款方式 文本展示用
 * @property string $ahead_repay 提前还款
 * @property string $overdue_desc 逾期政策
 * @property int $service_fee_type api 服务费扣除方式 1前置2后置
 * @property int $can_manual_repay api 是否支持主动还款1支持0不支持
 * @property string $manual_repay_detail api 主动还款 详情json
 * @property int $can_offline_repay api 是否支持线下还款 1支持 0 不支持 线下还款配置
 * @property string $offline_repay_detail api 线下还款 详情json
 * @property string $jump_url h5 跳转地址
 * @property int $is_multiple_app h5是否是多app
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $last_operator_id 上次操作人id
 */
class MkProductProperty extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'repay_type', 'ahead_repay', 'overdue_desc', 'manual_repay_detail', 'created_at', 'updated_at', 'last_operator_id'], 'required'],
            [['product_id', 'is_show_fee_txt', 'is_show_desc_entry', 'service_fee_type', 'can_manual_repay', 'can_offline_repay', 'is_multiple_app', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['manual_repay_detail', 'offline_repay_detail'], 'string'],
            [['hotline', 'offline_service'], 'string', 'max' => 32],
            [['robot_url', 'interest_desc', 'repay_type', 'ahead_repay', 'overdue_desc', 'jump_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品属性',
            'product_id' => 'product表主键id',
            'is_show_fee_txt' => 'api 息费说明是否展示0是不展示 1 展示',
            'is_show_desc_entry' => 'api 产品说明入口是否展示:0是不展示 1 展示',
            'hotline' => '客服电话',
            'offline_service' => '线下客服号',
            'robot_url' => '机器人客服地址',
            'interest_desc' => '利率说明',
            'repay_type' => '还款方式 文本展示用',
            'ahead_repay' => '提前还款',
            'overdue_desc' => '逾期政策',
            'service_fee_type' => 'api 服务费扣除方式 1前置2后置',
            'can_manual_repay' => 'api 是否支持主动还款1支持0不支持',
            'manual_repay_detail' => 'api 主动还款 详情json',
            'can_offline_repay' => 'api 是否支持线下还款 1支持 0 不支持 线下还款配置',
            'offline_repay_detail' => 'api 线下还款 详情json',
            'jump_url' => 'h5 跳转地址',
            'is_multiple_app' => 'h5是否是多app',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_operator_id' => '上次操作人id',
        ];
    }
}
