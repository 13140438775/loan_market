<?php
namespace common\models;

use common\models\mk\MkProductApiConfig;
use common\behaviors\OperatorBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ProductApiConfig extends MkProductApiConfig{


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
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

    /************************************* 枚举定义 end **********************************************/
    const API_INVOKE_TYPE_API = 1;

    static $api_invoke_type_set = [
        self::API_INVOKE_TYPE_API => '通用api',
    ];

    const NO_CREDIT_TYPE = 0;
    static $credit_type_set = [
        self::NO_CREDIT_TYPE => '无授信',
    ];

    const IS_NO_SIMPLE_RELOAN_FLOW = 0;
    static $is_simple_reloan_flow_set = [
        self::IS_NO_SIMPLE_RELOAN_FLOW => '否',
    ];

    const IS_NO_OUTER_AUTH_PRODUCT = 0;
    static $is_outer_auth_product_set = [
        self::IS_NO_OUTER_AUTH_PRODUCT => '否',
    ];

    const BIND_CARD_MODE_API = 1;
    const BIND_CARD_MODE_H5 = 2;
    const BIND_CARD_MODE_APIH5 = 3;
    static $bind_card_mode_set = [
        self::BIND_CARD_MODE_API => 'api',
        self::BIND_CARD_MODE_H5 => '跳转绑卡',
        self::BIND_CARD_MODE_APIH5 => '接口跳转绑卡',
    ];

    const REPAY_MODE_API = 1;
    const REPAY_MODE_H5 = 2;
    const REPAY_MODE_APIH5 = 3;
    static $repay_mode_set = [
        self::REPAY_MODE_API => 'api',
        self::REPAY_MODE_H5 => '跳转绑卡',
        self::REPAY_MODE_APIH5 => '接口跳转绑卡',
    ];

    const BIND_POSITION_AFTER_PUSH = 1;
    static $bind_position_set = [
        self::BIND_POSITION_AFTER_PUSH => '推单后审核前绑卡',
    ];

    const NO_CAN_LIST_CARD = 1;
    static $can_list_card_set = [
        self::NO_CAN_LIST_CARD => '不支持',
    ];

    const NO_CAN_CARD_SECOND_CONFIRM = 0;
    const CAN_CARD_SECOND_CONFIRM = 1;
    static $can_card_second_confirm_set = [
        self::NO_CAN_CARD_SECOND_CONFIRM => '不支持',
        self::CAN_CARD_SECOND_CONFIRM => '支持',
    ];

    const NO_CAN_REPLACE_CARD = 0;
    const CAN_REPLACE_CARD = 1;
    static $can_replace_card_set = [
        self::NO_CAN_REPLACE_CARD => '不支持',
        self::CAN_REPLACE_CARD => '支持',
    ];

    const IS_NO_UPDATE_AUDIT_LIMIT = 0;
    const IS_UPDATE_AUDIT_LIMIT = 1;
    static $is_update_audit_limit_set = [
        self::IS_NO_UPDATE_AUDIT_LIMIT => '否',
        self::IS_UPDATE_AUDIT_LIMIT => '是',
    ];

    const IS_NO_MARKET = 0;
    const IS_MARKET = 1;
    static $is_market_set = [
        self::IS_NO_MARKET => '否',
        self::IS_MARKET => '是',
    ];

    const IS_NO_H5_SIGN_PAGE = 0;
    const IS_H5_SIGN_PAGE = 1;
    static $is_h5_sign_page_set = [
        self::IS_NO_H5_SIGN_PAGE => '否',
        self::IS_H5_SIGN_PAGE => '是',
    ];

    /************************************* 枚举定义 end **********************************************/

    //api编辑场景
    const SCENARIO_API_CONFIG = 'api_config';

    public function scenarios()
    {
        return [
            self::SCENARIO_API_CONFIG => [
                'product_id', 'api_invoke_type' , 'credit_type', 'is_simple_reloan_flow', 'is_outer_auth_product', 'bind_card_mode',
                'bind_position', 'can_list_card', 'can_card_second_confirm', 'can_replace_card', 'api_url', 'api_ua',
                'api_secret', 'callback_plat_ua','callback_plat_secret', 'last_operator_id','bind_card_h5_url','whitelist',
                'is_update_audit_limit','is_market','is_h5_sign_page','h5_sign_url'
            ]
        ];
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
            [
                [
                    'product_id', 'api_invoke_type' , 'credit_type', 'is_simple_reloan_flow', 'is_outer_auth_product', 'bind_card_mode',
                    'bind_position', 'can_list_card', 'can_card_second_confirm', 'can_replace_card', 'api_url', 'api_ua',
                    'api_secret', 'callback_plat_ua','callback_plat_secret', 'last_operator_id','whitelist','is_update_audit_limit','is_market','is_h5_sign_page'
                ],
                'required',

                'on' => self::SCENARIO_API_CONFIG
            ],
            [
                'bind_card_h5_url','required',
                    'when' => function($model) {
                        return $model->bind_card_mode == 2;
                    },
                'on' => self::SCENARIO_API_CONFIG
            ],
            [
                'h5_sign_url','required',
                'when' => function($model) {
                    return $model->is_h5_sign_page == 1;
                },
                'on' => self::SCENARIO_API_CONFIG
            ],
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