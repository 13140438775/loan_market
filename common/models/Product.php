<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019-03-07
 * Time: 22:05
 */

namespace common\models;

use backend\models\validators\LinkageValidator;
use common\behaviors\OperatorBehavior;
use common\models\mk\MkMerchant;
use common\models\mk\MkProduct;
use common\models\mk\MkProductAssocTag;
use common\models\mk\MkProductSales;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Product extends MkProduct
{
    /************************************* visible 用户可见 操作定义end**********************************************/
    //@property int $visible 可见逻辑(位操作)
    // 100000000 第一位占位
    // 第二位1 老客户可见
    // 第三位1 新客户可见
    // 第四位1 复贷可见
    // 第五位1 首贷可见,其余是预留位
    const VISIBLE_VALID_BIT_LEN = 5;
    const OLD_VISIBLE = '1100000000';
    const NEW_VISIBLE = '1010000000';
    const SECOND_VISIBLE = '1001000000';
    const FIRST_VISIBLE = '1000100000';
    static $visible_set = [
        self::OLD_VISIBLE => '老客可见',
        self::NEW_VISIBLE => '新客可见',
        self::SECOND_VISIBLE => '复贷可见',
        self::FIRST_VISIBLE => '首贷可见'
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
     * 是否老客可见
     * isOldUserVisible
     * @date     2019-03-08 20:12
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isOldUserVisible()
    {
        return (strval($this->visible) & self::OLD_VISIBLE) === self::OLD_VISIBLE;
    }

    /**
     * 是否新客可见
     * isNewUserVisible
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isNewUserVisible()
    {
        return (strval($this->visible) & self::NEW_VISIBLE) === self::NEW_VISIBLE;
    }

    /**
     * 是否首贷可见
     * isFirstLoanVisible
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isFirstLoanVisible()
    {
        return (strval($this->visible) & self::FIRST_VISIBLE) === self::FIRST_VISIBLE;
    }

    /**
     * 是否复贷可见
     * isSecondLoanVisible
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isSecondLoanVisible()
    {
        return (strval($this->visible) & self::SECOND_VISIBLE) === self::SECOND_VISIBLE;
    }

    /**
     * 获取老客可见字值集合
     * getOldUserVisibleBitSet
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getOldUserVisibleBitSet()
    {
        return self::codeCollection(self::OLD_VISIBLE, self::VISIBLE_VALID_BIT_LEN);
    }

    /**
     * 获取新客可见字段值结合
     * getNewUserVisibleBitSet
     * @date     2019-03-08 20:15
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getNewUserVisibleBitSet()
    {
        return self::codeCollection(self::NEW_VISIBLE, self::VISIBLE_VALID_BIT_LEN);
    }

    /**
     * 获取首借用户可见字段值集合
     * getFirstLoanVisibleBitSet
     * @date     2019-03-08 20:15
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getFirstLoanVisibleBitSet()
    {
        return self::codeCollection(self::FIRST_VISIBLE, self::VISIBLE_VALID_BIT_LEN);
    }

    /**
     * 获取复借用户可见字段值集合
     * getSecondLoanVisibleBitSet
     * @date     2019-03-08 20:17
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getSecondLoanVisibleBitSet()
    {
        return self::codeCollection(self::SECOND_VISIBLE, self::VISIBLE_VALID_BIT_LEN);
    }
    /************************************* visible 用户可见 操作定义end **********************************************/

    /************************************* visible_mobile  手机端类型可见性 操作定义 begin **********************************************/
    //visible_mobile有效位长度
    const MOBILE_VISIBLE_BIT_LEN = 3;
    const IOS_VISIBLE = '1100000000';
    const ANDROID_VISIBLE = '1010000000';
    static $platform_visible_set = [
        self::IOS_VISIBLE => "ios可见",
        self::ANDROID_VISIBLE => "Android可见"
    ];

    /**
     * 是否IOS可见
     * isIosVisible
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isIosVisible()
    {
        return (strval($this->visible_mobile) & self::IOS_VISIBLE) === self::IOS_VISIBLE;
    }

    /**
     * 是否安卓端可见
     * isAndroidVisible
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isAndroidVisible()
    {
        return (strval($this->visible_mobile) & self::ANDROID_VISIBLE) === self::ANDROID_VISIBLE;
    }

    /**
     * 获取ios可见 字段可取值集合
     * getIosVisibleBitSet
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getIosVisibleBitSet()
    {
        return self::codeCollection(self::IOS_VISIBLE, self::MOBILE_VISIBLE_BIT_LEN);
    }

    /**
     * 获取android可见 字段可取值集合
     * getAndroidVisibleBitSet
     * @date     2019-03-08 20:36
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getAndroidVisibleBitSet()
    {
        return self::codeCollection(self::ANDROID_VISIBLE, self::MOBILE_VISIBLE_BIT_LEN);
    }

    /*************************************visible_mobile  手机端类型可见 操作定义 end **********************************************/

    /*************************************  scenario 适用场景 操作定义 end **********************************************/
    //场景字段有效位长度 逻辑位长4
    const SCENARIO_BIT_LEN = 5;
    const INDEX_BIG_SCENARIO = "1100000000";
    const INDEX_SMALL_SCENARIO = "1010000000";
    const LOAN_SCENARIO = "1001000000";
    const REFUSE_SCENARIO = "1000100000";
    static $scenario_set = [
        self::INDEX_BIG_SCENARIO => "首页大卡位",
        self::INDEX_SMALL_SCENARIO => "首页小卡位",
        self::LOAN_SCENARIO => "贷款大全",
        self::REFUSE_SCENARIO => "被拒推荐",
    ];

    /**
     * 获取适合首页大卡位场景 scenario 字段可取值集合
     * getIndexBigScenarioBitSet
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getIndexBigScenarioBitSet()
    {
        return self::codeCollection(self::INDEX_BIG_SCENARIO, self::SCENARIO_BIT_LEN);
    }

    /**
     * 获取适合 首页小卡位 场景 scenario 字段可取值集合
     * getIndexSmallScenarioBitSet
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getIndexSmallScenarioBitSet()
    {
        return self::codeCollection(self::INDEX_SMALL_SCENARIO, self::SCENARIO_BIT_LEN);
    }

    /**
     * 获取适合 贷款大全 场景 scenario 字段可取值集合
     * getLoanScenarioBitSet
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getLoanScenarioBitSet()
    {
        return self::codeCollection(self::LOAN_SCENARIO, self::SCENARIO_BIT_LEN);
    }

    /**
     * 获取适合 被拒 场景 scenario 字段可取值集合
     * getRefuseScenarioBitSet
     * @date     2019-03-08 20:35
     * @author   Wei Yang<suncode_666@163.com>
     * @return array
     * @throws \Exception
     */
    public static function getRefuseScenarioBitSet()
    {
        return self::codeCollection(self::REFUSE_SCENARIO, self::SCENARIO_BIT_LEN);
    }

    /*************************************  scenario 适用场景 操作定义 end **********************************************/
    /************************************* 排序用最低放款时间单位 sort_min_loan_time_type  枚举定义 end **********************************************/
    const SORT_MIN_LOAN_TIME_TYPE_MINUTE = 1;
    const SORT_MIN_LOAN_TIME_TYPE_HOUR = 2;
    const SORT_MIN_LOAN_TIME_TYPE_DAY = 3;
    static $sort_min_loan_time_type_set = [
        self::SORT_MIN_LOAN_TIME_TYPE_MINUTE => '分',
        self::SORT_MIN_LOAN_TIME_TYPE_HOUR => '时',
        self::SORT_MIN_LOAN_TIME_TYPE_DAY => '日'
    ];
    /*************************************  sort_min_loan_time_type 枚举定义 操作定义 end **********************************************/

    /*************************************  interest_pay_type 枚举定义 操作定义 end **********************************************/
    const INTEREST_PAY_TYPE_FKSKX = 1;
    const INTEREST_PAY_TYPE_XXHB = 2;
    const INTEREST_PAY_TYPE_DEBX = 3;
    const INTEREST_PAY_TYPE_DQHBX = 4;
    const INTEREST_PAY_TYPE_OTHER = 5;
    static $interest_pay_type_set = [
        self::INTEREST_PAY_TYPE_FKSKX => '放款时扣息',
        self::INTEREST_PAY_TYPE_XXHB => '先息后本',
        self::INTEREST_PAY_TYPE_DEBX => '等额本息',
        self::INTEREST_PAY_TYPE_DQHBX => '到期还本息',
        self::INTEREST_PAY_TYPE_OTHER => '其他'
    ];
    /*************************************  interest_pay_type 枚举定义 操作定义 end **********************************************/

    /************************************* visible 用户可见 操作定义end**********************************************/

    /************************************* filter_net_time 用户可见 操作定义end**********************************************/
    //@property int $filterNetTime 可见逻辑(位操作)
    // 1000000000 第一位占位
    // 第二位1 >=1年
    // 第三位1 6个月 至 1年
    // 第四位1 3个月-至 6个月
    // 第五位1 小于三个月
    const MORE_THAN_ONE_YEAR = '1100000000';
    const BETWEEN_SIX_AND_ONE_YEAR = '1010000000';
    const BETWEEN_THREE_AND_SIX = '1001000000';
    const LESS_THAN_THREE = '1000100000';
    static $visible_filter_set = [
        self::MORE_THAN_ONE_YEAR => '大于一年',
        self::BETWEEN_SIX_AND_ONE_YEAR => '六个月至一年',
        self::BETWEEN_THREE_AND_SIX => '三个月至六个月',
        self::LESS_THAN_THREE => '小于三个月'
    ];


    /**
     * 是否 >=1年
     * isOldUserVisible
     * @date     2019-03-08 20:12
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isMoreThanOneYear()
    {
        return (strval($this->filter_net_time) & self::MORE_THAN_ONE_YEAR) === self::MORE_THAN_ONE_YEAR;
    }

    /**
     * 是否6个月 至 1年
     * isNewUserVisible
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isBetweenSixAndOneYear()
    {
        return (strval($this->filter_net_time) & self::BETWEEN_SIX_AND_ONE_YEAR) === self::BETWEEN_SIX_AND_ONE_YEAR;
    }

    /**
     * 是否3个月-至 6个月
     * isFirstLoanVisible
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isBetWeenThreeAndSix()
    {
        return (strval($this->filter_net_time) & self::BETWEEN_THREE_AND_SIX) === self::BETWEEN_THREE_AND_SIX;
    }

    /**
     * 是否小于三个月
     * isSecondLoanVisible
     * @date     2019-03-08 20:13
     * @author   Wei Yang<suncode_666@163.com>
     * @return int
     */
    public function isLessThanThree()
    {
        return (strval($this->filter_net_time) & self::LESS_THAN_THREE) === self::LESS_THAN_THREE;
    }

    /************************************* filter_net_time 用户可见 操作定义end **********************************************/

    /************************************* 产品类型 product_type 枚举定义 begin **********************************************/

    const ABUTMENT_TYPE_API = 0;
    const ABUTMENT_TYPE_H5 = 1;
    static $call_type_set = [
        self::ABUTMENT_TYPE_API => 'api对接',
        self::ABUTMENT_TYPE_H5 => 'H5对接',
    ];

    const PRODUCT_TYPE_SINGLE = 1;
    const PRODUCT_TYPE_MULTIPLE = 2;
    static $product_type_set = [
        self::PRODUCT_TYPE_SINGLE => '单期',
        self::PRODUCT_TYPE_MULTIPLE => '多期'
    ];
    /************************************* 产品类型 product_type 枚举定义 end **********************************************/

    /************************************* 是否固定期限 is_fixed_step 枚举定义 begin **********************************************/
    const IS_FIXED_STEP = 1;
    const IS_NOT_FIXED_STEP = 2;
    static $is_fixed_step_set = [
        self::IS_FIXED_STEP => '固定期限粒度',
        self::IS_NOT_FIXED_STEP => '非固定期限粒度'
    ];
    /************************************* 是否固定期限 is_fixed_step 枚举定义 end **********************************************/

    /************************************* 期限范围 term_type 枚举定义 begin **********************************************/
    const TERM_TYPE_DAY = 1;
    const TERM_TYPE_MONTH = 2;
    const TERM_TYPE_YEAR = 3;
    static $term_type_set = [
        self::TERM_TYPE_DAY => '日',
        self::TERM_TYPE_MONTH => '月',
        self::TERM_TYPE_YEAR => '年'
    ];
    /************************************* 期限范围 term_type 枚举定义 end **********************************************/

    /************************************* 是否统一费率 is_same_interest 枚举定义 end **********************************************/
    const IS_SAME_INTEREST = 1;
    const IS_NOT_SAME_INTEREST = 0;
    static $is_same_interest_set = [
        self::IS_SAME_INTEREST => '是',
        self::IS_NOT_SAME_INTEREST => '否'
    ];
    /************************************* 是否统一费率 is_same_interest 枚举定义 end **********************************************/

    /************************************* 是否启用客群筛选 is_customer_screen 枚举定义 start **********************************************/
    const IS_CUSTOMER_SCREEN = 1;
    const IS_NOT_CUSTOMER_SCREEN = 0;
    static $is_customer_screen_set = [
        self::IS_CUSTOMER_SCREEN => '是',
        self::IS_NOT_CUSTOMER_SCREEN => '否'
    ];
    /************************************* 是否启用客群筛选 is_customer_screen 枚举定义 end **********************************************/


    /************************************* 是否开启手机号码黑名单 is_mobile_black 枚举定义 start **********************************************/
    const IS_MOBILE_BLACK = 1;
    const IS_NOT_MOBILE_BLACK = 0;
    static $is_mobile_black_set = [
        self::IS_MOBILE_BLACK => '是',
        self::IS_NOT_MOBILE_BLACK => '否'
    ];
    /************************************* 是否开启手机号码黑名单 is_mobile_black 枚举定义 end **********************************************/

    /************************************* 是否开启手机号码黑名单 is_mobile_black 枚举定义 start **********************************************/
    const IS_CAREER_AUTO = 1;
    const IS_NOT_CAREER_AUTO = 0;
    static $is_career_auto_set= [
        self::IS_CAREER_AUTO => '是',
        self::IS_NOT_CAREER_AUTO => '否'
    ];
    /************************************* 是否开启手机号码黑名单 is_mobile_black 枚举定义 end **********************************************/

    //api编辑场景
    const SCENARIO_API_EDIT = 'api_edit';
    //h5编辑场景
    const SCENARIO_H5_EDIT = 'h5_edit';
    //费率配置
    const SCENARIO_FEE_CONFIG_EDIT = 'fee_config';
    // 权重
    const SCENARIO_SET_WEIGHT = 'set_weight';
    // 线上配置
    const SCENARIO_SET_ONLINE_CONFIG = 'set_online_config';
    //标签配置
    const SCENARIO_SET_TAG_CONFIG = 'set_tag_config';
    //客群筛选
    const SCENARIO_CUSTOMER_SCREEN = 'customer_screen';
    //控量配置
    const SCENARIO_SET_SIZE_CONFIG = 'set_size_config';
    //控量配置
    const SCENARIO_SET_SCENE_CONFIG = 'set_scene_config';
    //手填项 职业联动
    const SCENARIO_HAND_FILL_CONFIG = 'hand_fill_config';

    public function scenarios()
    {
        return [
            //编辑api场景
            self::SCENARIO_API_EDIT => [
                'name', 'merchant_id', 'logo_url', 'description', 'sort_min_loan_time',
                'show_min_loan_time', 'show_interest_desc', 'show_amount_range',
                'interest_day', 'interest_pay_type', 'interest_pay_type_desc'
            ],
            self::SCENARIO_H5_EDIT => [
                'name', 'merchant_id', 'logo_url', 'description', 'sort_min_loan_time',
                'show_min_loan_time', 'show_interest_desc', 'show_amount_range',
                'interest_day', 'interest_pay_type', 'interest_pay_type_desc'
            ],
            self::SCENARIO_FEE_CONFIG_EDIT => [
                'product_type', 'is_fixed_step', 'is_same_interest', 'term_type', 'min_term', 'max_term',
                'incr_step', 'single_interest', 'single_fee', 'max_amount', 'min_amount','incr_amount_step'
            ],
            self::SCENARIO_SET_WEIGHT => [
                'weight'
            ],
            self::SCENARIO_SET_ONLINE_CONFIG => [
                'show_name','description','sort_min_loan_time','sort_min_loan_time_type','interest_day',
                'show_min_loan_time','show_interest_desc','show_amount_range','show_avg_term','interest_pay_type_desc'
            ],
            self::SCENARIO_SET_TAG_CONFIG => [
                'show_tag_id'
            ],
            self::SCENARIO_CUSTOMER_SCREEN => [
                'filter_user_enable','enable_mobile_black','min_age','max_age','area_filter'
            ],
            self::SCENARIO_SET_SIZE_CONFIG => [
                'enable_count_limit','is_time_sharing','limit_begin_time','limit_end_time','uv_day_limit','is_diff_first',
                'is_diff_plat','first_loan_one_push_limit','first_loan_approval_limit','second_loan_one_push_limit','second_loan_approval_limit'
            ],
            self::SCENARIO_SET_SCENE_CONFIG => [
//                'online_scenario','visible','visible_mobile'
            ],
            self::SCENARIO_HAND_FILL_CONFIG => [
                'is_career_auto'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'merchant_id', 'logo_url', 'description', 'sort_min_loan_time',
                'show_min_loan_time', 'show_interest_desc', 'show_amount_range', 'max_amount', 'min_amount',
                'interest_day', 'interest_pay_type', 'interest_pay_type_desc', 'show_tag_id', 'created_at',
                'updated_at', 'last_operator_id', 'visible'], 'required'],
            [['merchant_id', 'sort_min_loan_time', 'sort_min_loan_time_type', 'max_amount', 'min_amount',
                'interest_day', 'interest_pay_type', 'show_tag_id', 'created_at', 'updated_at',
                'call_type', 'product_type', 'is_fixed_step', 'incr_step', 'is_same_interest',
                'term_type', 'min_term', 'max_term', 'single_interest', 'single_fee', 'last_operator_id',
                'weight', 'filter_user_enable', 'enable_mobile_black', 'min_age', 'max_age',
                'filter_net_time', 'online_scenario', 'visible', 'visible_mobile',
                'enable_count_limit', 'is_time_sharing', 'limit_begin_time', 'limit_end_time',
                'uv_day_limit', 'is_diff_first', 'is_diff_plat', 'first_loan_one_push_limit',
                'first_loan_approval_limit', 'second_loan_one_push_limit', 'second_loan_approval_limit', 'config_status',
                'display_status'], 'integer'],
            [['name', 'show_name', 'logo_url', 'description', 'show_min_loan_time', 'show_interest_desc', 'show_amount_range', 'show_avg_term', 'interest_pay_type_desc', 'area_filter'], 'string', 'max' => 255],
            [['sort_min_loan_time_type'], 'default', 'value' => 2],//默认时间类型是小时

            //api场景规则配置
            [['name', 'merchant_id', 'logo_url', 'description', 'sort_min_loan_time',
                'show_min_loan_time', 'show_interest_desc', 'show_amount_range',
                'interest_day', 'interest_pay_type', 'interest_pay_type_desc',
                'visible'], 'required', 'on' => self::SCENARIO_API_EDIT],
            [
                [
                    'product_type', 'is_fixed_step', 'is_same_interest', 'term_type', 'min_term', 'max_term',
                     'single_interest', 'single_fee', 'max_amount', 'min_amount','incr_amount_step'
                ],
                'required',
                'on' => self::SCENARIO_FEE_CONFIG_EDIT
            ],
            [//非固定期限粒度 期限粒度不必填 固定期限粒度 期限粒度必填
                'incr_step','required','when' => function($model) {
                    return $model->is_fixed_step == '1';
                },'on' => self::SCENARIO_FEE_CONFIG_EDIT
            ],
            [//非固定期限粒度 期限粒度不必填 固定期限粒度 期限粒度必填
                ['single_fee','incr_step'],'integer','on' => self::SCENARIO_FEE_CONFIG_EDIT
            ],
            [//如果是统一费率
                'single_fee','required','when' => function($model) {
                    return $model->is_same_interest == '1';
                },'on' => self::SCENARIO_FEE_CONFIG_EDIT
            ],

            //配置权重场景
            [
                'weight','validateWeight','on' => self::SCENARIO_SET_WEIGHT
            ],

            //配置线上场景
            [
                [
                    'show_name','description','sort_min_loan_time','sort_min_loan_time_type','interest_day',
                    'show_min_loan_time','show_interest_desc','show_amount_range','show_avg_term','interest_pay_type_desc'
                ],
                'required',
                'on' => self::SCENARIO_SET_ONLINE_CONFIG
            ],
            //标签配置
            [
                [
                    'show_tag_id'
                ],
                'required','on' => self::SCENARIO_SET_TAG_CONFIG
            ],
            //客群配置
            [
                [
                    'filter_user_enable','enable_mobile_black','min_age','max_age','area_filter'
                ],
                'required',
                'on' => self::SCENARIO_CUSTOMER_SCREEN
            ],
            [
                'filter_user_enable','required',
                'when' => function($model) {
                    return $model->bind_card_mode == 0;
                },
                'on' => self::SCENARIO_CUSTOMER_SCREEN
            ],
            //控量配置
            [
                [
                    'enable_count_limit','is_time_sharing','limit_begin_time','limit_end_time','uv_day_limit','is_diff_first',
                    'is_diff_plat','first_loan_one_push_limit','first_loan_approval_limit','second_loan_one_push_limit','second_loan_approval_limit'
                ],
                'required',
                'on' => self::SCENARIO_SET_SIZE_CONFIG
            ],
            [
                [
//                    'online_scenario','visible','visible_mobile'
                ],
                'required',
                'on' => self::SCENARIO_SET_SCENE_CONFIG
            ],
            [
                'is_career_auto','required','on'=>self::SCENARIO_HAND_FILL_CONFIG
            ]

        ];
    }
    public function validateWeight($attribute, $params)
    {
        if ($this->$attribute < 0 || $this->$attribute >= 100) {
            $this->addError($attribute, '权重值,范围0到100 两位有效数字');
        }
    }

    public function getTagIds()
    {
        return $this->getProductAssocTag()->select('tag_id')->column();
//        $sql = $this->getProductAsocTag()->select('tag_id')->createCommand()->getRawSql();
    }

    public function getProductAssocTag()
    {
        return $this->hasMany(ProductAssocTag::class, ['product_id' => 'id']);
    }

    public function getAppIds()
    {
        return $this->getApps()->select('app_id')->column();
//        $sql = $this->getProductAsocTag()->select('tag_id')->createCommand()->getRawSql();
    }

    public function getApps()
    {
        return $this->hasMany(ProductPlatLimit::class, ['product_id' => 'id']);
    }

    public static function getAppsIdMapName(){
        $result = Apps::find()->select('id, app_name')->asArray()->all();
        return array_column($result,'app_name','id');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品表',
            'name' => '产品名称',
            'merchant_id' => '所属公司id merchant主键id',
            'show_name' => '展示名称',
            'logo_url' => 'logo地址',
            'description' => '产品简介',
            'sort_min_loan_time' => '(排序)最快放款时间(统一转化分钟)',
            'sort_min_loan_time_type' => '(排序)最快放款时间单位1分2小时3天',
            'show_min_loan_time' => '(展示)最快放款时间',
            'show_interest_desc' => '(展示)息费说明',
            'show_amount_range' => '(展示)额度范围',
            'max_amount' => '最高实际额度范围',
            'min_amount' => '最低实际额度范围',
            'interest_day' => '(排序)实际日息%存整形除以100',
            'show_avg_term' => '(展示)期限范围',
            'interest_pay_type' => '息费收取方式1 放款时扣息2 先息后本 3 等额本息 4 到期还本息  5 其他',
            'interest_pay_type_desc' => '其他息费方式说明 type为5',
            'show_tag_id' => '展示标签',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'call_type' => '调用方式0 api 1 h5',
            'product_type' => 'api 产品属性1单期 2 多期',
            'is_fixed_step' => 'api 是否是固定期限粒度1固定期限粒度 2非固定期限粒度',
            'incr_step' => 'api固定期限粒度步长',
            'incr_amount_step' => 'api 金额粒度步长(分)',
            'is_same_interest' => 'api 是否统一费率 1是 0否',
            'term_type' => 'api 期限范围 1 日 2 月 3 年',
            'min_term' => 'api 最低期限',
            'max_term' => 'api 最高期限',
            'single_interest' => 'api 每期利率',
            'single_fee' => 'api 每期费率',
            'last_operator_id' => '上次操作人id',
            'weight' => '权重',
            'filter_user_enable' => '是否启用客群筛选0 否1 启用',
            'enable_mobile_black' => '是否启用手机黑名单0 否1启用',
            'min_age' => '年龄下限',
            'max_age' => '年龄上限',
            'area_filter' => '地域过滤 身份证前三位或者前6位',
            'filter_net_time' => '手机过滤时长 (位操作) 1000000000 10位第一位占位 9位有效位 选中1 反之0',
            'online_scenario' => '场景配置(位操作) 100000000 10位 9位有效位 第一位占位 第二位 是的首页大卡未 第二位是首页小卡位 第三位是贷款大全 第四位 被拒推荐',
            'visible' => '可见逻辑(位操作) 100000000 第一位占位 第二位老客户可见 第二位 新客户可见 第三位复贷可见 第四位首贷可见',
            'visible_mobile' => '可见端(位操作)1000000000 第一位占位 第二位ios可见 第三位安卓可见',
            'enable_count_limit' => '是否开始限量配置',
            'is_time_sharing' => '是否分时段',
            'limit_begin_time' => '放量限制开始时间',
            'limit_end_time' => '放量限制结束时间',
            'uv_day_limit' => 'uv单日控量',
            'is_diff_first' => '是否区分首复贷控量',
            'is_diff_plat' => '是否区分平台控量',
            'first_loan_one_push_limit' => '首贷一推单量控制',
            'first_loan_approval_limit' => '首贷审核单量控制',
            'second_loan_one_push_limit' => '复贷一推单量控制',
            'second_loan_approval_limit' => '复贷审核订单量控制',
            'config_status' => '是否上架状态 0 配置中 1 B端下架 2B端下架',
            'display_status' => '运营上下架0 下架 1 上架',
        ];
    }


    public function getMerchant()
    {
        return $this->hasOne(Merchant::class, ['id' => 'merchant_id']);
    }


}