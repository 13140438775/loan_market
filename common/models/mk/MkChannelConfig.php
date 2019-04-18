<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_channel_config".
 *
 * @property int $id 渠道配置表主键id
 * @property int $channel_id 渠道id
 * @property string $channel_name 渠道名字
 * @property int $platform_type 平台类型 1 用钱金卡
 * @property int $package_id 包id
 * @property int $cooperate_mode 合作方式 1CPA 2CPC 3CPS 4UV 5免费
 * @property int $is_general_package 是否通用包  0 否 1是
 * @property string $unsign_in_begin_version 未登录贷超 不展示开始版本
 * @property string $unsign_in_end_version 未登录贷超 不展示结束版本号
 * @property string $sign_in_begin_version 登录贷超  不展示开始版本号
 * @property string $sign_in_end_version 登录贷超 不展示结束版本号
 * @property int $is_show_loan_user 是否只对放款用户展示 0否 1是
 * @property int $show_day 登录用户指定时间展示 0 不限制 1 ，2，3，4，5 天
 * @property int $delivery_terminal 投放端  安卓 1100000000   ios企业 1010000000 ios官方 1001000000 
 * @property int $h5_template_id h5 模板id
 * @property int $status 状态 1配置中，2启用，0禁用
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_operator_id 最后操作人id
 */
class MkChannelConfig extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_channel_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'platform_type', 'package_id', 'cooperate_mode', 'is_general_package', 'is_show_loan_user', 'show_day', 'delivery_terminal', 'h5_template_id', 'status','created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['channel_name'], 'string', 'max' => 50],
            [['unsign_in_begin_version', 'unsign_in_end_version', 'sign_in_begin_version', 'sign_in_end_version'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '渠道配置表主键id',
            'channel_id' => '渠道id',
            'channel_name' => '渠道名字',
            'platform_type' => '平台类型 1 用钱金卡',
            'package_id' => '包id',
            'cooperate_mode' => '合作方式 1CPA 2CPC 3CPS 4UV 5免费',
            'is_general_package' => '是否通用包  0 否 1是',
            'unsign_in_begin_version' => '未登录贷超 不展示开始版本',
            'unsign_in_end_version' => '未登录贷超 不展示结束版本号',
            'sign_in_begin_version' => '登录贷超  不展示开始版本号',
            'sign_in_end_version' => '登录贷超 不展示结束版本号',
            'is_show_loan_user' => '是否只对放款用户展示 0否 1是',
            'show_day' => '登录用户指定时间展示 0 不限制 1 ，2，3，4，5 天',
            'delivery_terminal' => '投放端  安卓 1100000000   ios企业 1010000000 ios官方 1001000000 ',
            'h5_template_id' => 'h5 模板id',
            'status' => '状态 1配置中，2启用，0禁用',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_operator_id' => '最后操作人id',
        ];
    }
}
