<?php
namespace common\models;

use common\models\mk\MkChannelConfig;
use common\behaviors\OperatorBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ChannelConfig extends MkChannelConfig {

    /************************************* 枚举定义 end **********************************************/
    const PLAT_TYPE_MONEY_CARD = 1;
    static $plat_type_set = [
        self::PLAT_TYPE_MONEY_CARD => '用钱金卡',
    ];


    const COOPERATE_MODE_CPA= 1;
    const COOPERATE_MODE_CPC  = 2;
    const COOPERATE_MODE_CPS = 3;
    const COOPERATE_MODE_UV = 4;
    const COOPERATE_MODE_FREE = 5;
    const cooperate_mode_map = [
        self::COOPERATE_MODE_CPA => 'CPA',
        self::COOPERATE_MODE_CPC => 'CPC',
        self::COOPERATE_MODE_CPS => 'CPS',
        self::COOPERATE_MODE_UV => 'UV',
        self::COOPERATE_MODE_FREE => '免费',
    ];

    const NO_GENERAL_PACKAGE = 0; // 不是通用包
    const GENERAL_PACKAGE = 1; // 通用包
    const is_general_package_map = [
        self::NO_GENERAL_PACKAGE => '否',
        self::GENERAL_PACKAGE => '是',
    ];

    const STATUS_CONFIGURATION = 1;
    const STATUS_USERD = 2;
    const STATUS_UNUSERD = 0;
    const status_map = [
        self::STATUS_CONFIGURATION => '配置中',
        self::STATUS_USERD => '启用',
        self::STATUS_UNUSERD => '禁用',
    ];


    const ANDROID = 1100000000;
    const IOS_COMPANY = 1010000000;
    const IOS = 1001000000;
    const put_in_map = [
        self::ANDROID => '安卓',
        self::IOS_COMPANY => 'IOS企业',
        self::IOS => 'IOS官方',
    ];

    const IS_NO_SHOW_LOAN_USER = 0; // 不是通用包
    const IS_SHOW_LOAN_USER = 1; // 通用包
    const is_show_loan_user_map = [
        self::IS_NO_SHOW_LOAN_USER => '否',
        self::IS_SHOW_LOAN_USER => '是',
    ];

    const SHOW_DAY_ONE= 1;
    const SHOW_DAY_TWO  = 2;
    const SHOW_DAY_THREE = 3;
    const SHOW_DAY_FOUR = 4;
    const SHOW_DAY_FIVE = 5;
    const SHOW_DAY_FREE = 6;
    const show_day_map = [
        self::SHOW_DAY_ONE => '1',
        self::SHOW_DAY_TWO => '2',
        self::SHOW_DAY_THREE => '3',
        self::SHOW_DAY_FOUR => '4',
        self::SHOW_DAY_FIVE => '5',
        self::SHOW_DAY_FREE => '不限制',
    ];


    /************************************* 枚举定义 end **********************************************/

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


    public function getPackageName()
    {
        $result = Package::find()->select('id, package_name')->asArray()->all();
        return array_column($result,'package_name','id');
    }

    public function getH5Template()
    {
        $result = H5Template::find()->select('id, h5_template_name')->asArray()->all();
        return array_column($result,'h5_template_name','id');
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'platform_type', 'package_id', 'cooperate_mode', 'is_general_package', 'is_show_loan_user', 'show_day', 'delivery_terminal', 'h5_template_id', 'status','created_at', 'updated_at', 'last_operator_id'], 'integer'],
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