<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_api_config".
 *
 * @property int $id
 * @property int $product_id 产品主键ID
 * @property int $api_invoke_type api接入形势 目前1通用api
 * @property int $credit_type 授信类型 0 无授信
 * @property int $is_simple_reloan_flow 是否支持复贷简化流程 默认否
 * @property int $is_outer_auth_product 是否为请求外部获取认证地址产品 默认否
 * @property int $is_update_audit_limit 是否修改审核额度 1是 0否
 * @property int $is_market 是否有商城模式 1是 0否
 * @property int $is_h5_sign_page 是否有H5签约页面 1是 0否
 * @property string $h5_sign_url h5 签约url 只有开启h5 签约生效
 * @property int $bind_card_mode 绑卡模式1 api 2 跳转绑卡 3 接口跳转绑卡
 * @property string $bind_card_h5_url h5模式绑卡跳转地址
 * @property int $bind_position 绑卡位置 1 推单后审核前绑卡
 * @property int $repay_mode 还款模式1 api 2 跳转还款 3 接口跳转还款
 * @property string $repay_h5_url 还款h5url
 * @property int $can_list_card 是否支持已绑定卡列表0 不支持
 * @property int $can_card_second_confirm 是否支持统一卡二次确认 默认支持
 * @property int $can_replace_card 是否支持更换还款银行卡 默认支持
 * @property string $api_url 通用api请求地址
 * @property string $api_ua 通用api请求UA
 * @property string $api_secret 通用api请求秘钥
 * @property string $callback_plat_ua 通用api回调平台接口UA
 * @property string $callback_plat_secret 通用api回调平台接口秘钥
 * @property string $whitelist 白名单
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_operator_id 上次操作人id
 */
class MkProductApiConfig extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_api_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'is_outer_auth_product', 'api_url', 'api_ua', 'api_secret', 'callback_plat_ua', 'callback_plat_secret', 'whitelist', 'created_at', 'updated_at', 'last_operator_id'], 'required'],
            [['product_id', 'api_invoke_type', 'credit_type', 'is_simple_reloan_flow', 'is_outer_auth_product', 'is_update_audit_limit', 'is_market', 'is_h5_sign_page', 'bind_card_mode', 'bind_position', 'repay_mode', 'can_list_card', 'can_card_second_confirm', 'can_replace_card', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['whitelist'], 'string'],
            [['h5_sign_url', 'bind_card_h5_url', 'repay_h5_url', 'api_url', 'api_ua', 'api_secret', 'callback_plat_ua', 'callback_plat_secret'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '产品主键ID',
            'api_invoke_type' => 'api接入形势 目前1通用api',
            'credit_type' => '授信类型 0 无授信',
            'is_simple_reloan_flow' => '是否支持复贷简化流程 默认否',
            'is_outer_auth_product' => '是否为请求外部获取认证地址产品 默认否',
            'is_update_audit_limit' => '是否修改审核额度 1是 0否',
            'is_market' => '是否有商城模式 1是 0否',
            'is_h5_sign_page' => '是否有H5签约页面 1是 0否',
            'h5_sign_url' => 'h5 签约url 只有开启h5 签约生效',
            'bind_card_mode' => '绑卡模式1 api 2 跳转绑卡 3 接口跳转绑卡',
            'bind_card_h5_url' => 'h5模式绑卡跳转地址',
            'bind_position' => '绑卡位置 1 推单后审核前绑卡',
            'repay_mode' => '还款模式1 api 2 跳转还款 3 接口跳转还款',
            'repay_h5_url' => '还款h5url',
            'can_list_card' => '是否支持已绑定卡列表0 不支持',
            'can_card_second_confirm' => '是否支持统一卡二次确认 默认支持',
            'can_replace_card' => '是否支持更换还款银行卡 默认支持',
            'api_url' => '通用api请求地址',
            'api_ua' => '通用api请求UA',
            'api_secret' => '通用api请求秘钥',
            'callback_plat_ua' => '通用api回调平台接口UA',
            'callback_plat_secret' => '通用api回调平台接口秘钥',
            'whitelist' => '白名单',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_operator_id' => '上次操作人id',
        ];
    }
}
