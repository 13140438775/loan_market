<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%credit_product}}".
 *
 * @property int $id product_id
 * @property string $product_name 产品名称
 * @property int $product_phone 商家电话
 * @property string $product_qq 商家qq
 * @property string $product_features 产品特色
 * @property string $product_desc 产品描述
 * @property int $product_type 产品类型:[1:借贷产品 2:信用卡产品 3:大额合规产品]
 * @property int $up_time 上架时间
 * @property int $product_status 产品状态:[1:上架 2:下架 3:临时下架 4:待上架]
 * @property string $apply_conditions 申请条件
 * @property int $min_credit 最小贷额度
 * @property int $max_credit 最大贷额度
 * @property int $rate_type 利率类型:[1:日 2:月 3:年]
 * @property int $rate_num 利率百分比
 * @property int $min_credit_days 借款期限范围
 * @property int $max_credit_days 借款期限范围
 * @property int $credit_limit_type 借款期限类型:[1:日 2:月 3:年]
 * @property int $avg_credit_days 平均借款期限
 * @property int $avg_credit_limit_type 平均借款期限类型:[1:日 2:月 3:年]
 * @property int $fast_loan 最快放款时间
 * @property int $fast_loan_type 最快放款时间类型:[1:分钟 2:小时 3:天]
 * @property string $url 地址
 * @property string $logo_url logo地址
 * @property string $apply_materia 申请所需材料json
 * @property int $credit_base 借款人基数
 * @property int $tag_id 展示标签
 * @property int $uv_limit UV控量
 * @property int $sort 排序
 * @property int $is_inner [0:外部产品 1:内部产品]
 * @property int $is_valid [0:无效     1:有效]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class CreditProduct extends \yii\db\ActiveRecord
{
    const APPLY_MATERIA_FACE = 1;
    const APPLY_MATERIA_CARD = 2;
    const APPLY_MATERIA_CREDIT = 3;
    const APPLY_MATERIA_REPORT = 4;
    const APPLY_MATERIA_CREDIT_CARD = 5;
    const APPLY_MATERIA_FUND = 6;
    const APPLY_MATERIA_JINGDONG = 7;
    const APPLY_MATERIA_TAOBAO = 8;
    public static $apply_materia_set = [
        self::APPLY_MATERIA_FACE => "人脸识别",
        self::APPLY_MATERIA_CARD => "身份认证",
        self::APPLY_MATERIA_CREDIT => "芝麻信用",
        self::APPLY_MATERIA_REPORT => "运营商报告",
        self::APPLY_MATERIA_CREDIT_CARD => "信用卡",
        self::APPLY_MATERIA_FUND => "公积金",
        self::APPLY_MATERIA_JINGDONG => "京东账号",
        self::APPLY_MATERIA_TAOBAO => "淘宝账号"
    ];

    const PRODUCT_TYPE_DEBIT = 1;
    const PRODUCT_TYPE_CREDIT = 2;
    const PRODUCT_TYPE_LARGE = 3;
    public static $product_type_set = [
        self::PRODUCT_TYPE_DEBIT => '借贷产品',
        self::PRODUCT_TYPE_CREDIT => '信用卡产品',
        self::PRODUCT_TYPE_LARGE => '大额合规产品'
    ];

    const PRODUCT_STATUS_ALL = 0;
    const PRODUCT_STATUS_UP = 1;
    const PRODUCT_STATUS_TEMP_UP = 2;
    const PRODUCT_STATUS_TEMP_DOWN = 3;
    const PRODUCT_STATUS_DOWN = 4;

    public static $product_status_set = [
        self::PRODUCT_STATUS_UP => '上架',
        self::PRODUCT_STATUS_TEMP_UP => '待上架',
        self::PRODUCT_STATUS_TEMP_DOWN => '临时下架',
        self::PRODUCT_STATUS_DOWN => '下架'
    ];

    const RATE_TYPE_D = 1;
    const RATE_TYPE_M = 2;
    const RATE_TYPE_Y = 3;
    public static $rate_type_set = [
        self::RATE_TYPE_D => '日',
        self::RATE_TYPE_M => '月',
        self::RATE_TYPE_Y => '年'
    ];

    const CREDIT_LIMIT_TYPE_D = 1;
    const CREDIT_LIMIT_TYPE_M = 2;
    const CREDIT_LIMIT_TYPE_Y = 3;
    public static $credit_limit_type_set = [
        self::CREDIT_LIMIT_TYPE_D => '日',
        self::CREDIT_LIMIT_TYPE_M => '月',
        self::CREDIT_LIMIT_TYPE_Y => '年'
    ];

    const AVG_CREDIT_LIMIT_TYPE_D = 1;
    const AVG_CREDIT_LIMIT_TYPE_M = 2;
    const AVG_CREDIT_LIMIT_TYPE_Y = 3;
    public static $avg_credit_limit_type_set = [
        self::AVG_CREDIT_LIMIT_TYPE_D => '日',
        self::AVG_CREDIT_LIMIT_TYPE_M => '月',
        self::AVG_CREDIT_LIMIT_TYPE_Y => '年'
    ];

    const FAST_LOAN_TYPE_I = 1;
    const FAST_LOAN_TYPE_H = 2;
    const FAST_LOAN_TYPE_D = 3;
    public static $fast_loan_type_set = [
        self::FAST_LOAN_TYPE_I => '分钟',
        self::FAST_LOAN_TYPE_H => '小时',
        self::FAST_LOAN_TYPE_D => '天'
    ];

    const IS_INVALID = 0;
    const IS_VALID = 1;
    public static $is_valid_set = [
        self::IS_INVALID => '无效',
        self::IS_VALID => '有效'
    ];
    //后台编辑增加的场景下 up_time转时间戳
//    const SCENARIO_BACKEND_CREATE_UPDATE = 'backend_create_update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%credit_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_name', 'product_type', 'product_desc', 'product_status', 'min_credit', 'max_credit', 'rate_type', 'rate_num', 'min_credit_days', 'max_credit_days', 'credit_limit_type', 'avg_credit_days', 'avg_credit_limit_type', 'fast_loan', 'fast_loan_type', 'credit_base', 'is_valid', 'url'], 'required'],
            [['product_phone', 'product_type', 'up_time', 'product_status', 'min_credit', 'max_credit', 'rate_type', 'min_credit_days', 'max_credit_days', 'credit_limit_type', 'avg_credit_days', 'avg_credit_limit_type', 'fast_loan', 'fast_loan_type', 'credit_base', 'tag_id', 'uv_limit', 'sort', 'is_inner', 'is_valid', 'created_at', 'updated_at'], 'integer'],
            [['rate_num'], 'number'],
            [['product_name', 'product_features', 'product_desc', 'apply_conditions', 'apply_materia'], 'string', 'max' => 255],
            [['up_time', 'product_type', 'tag_id'], 'default', 'value' => '0'],
            [['is_inner', 'is_valid'], 'default', 'value' => '1'],
            [['logo_url'], 'default', 'value' => ''],
            [['product_qq'], 'string', 'max' => 50],
            [['url', 'logo_url'], 'string', 'max' => 1000],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品ID',
            'product_name' => '产品名称',
            'product_phone' => '商家电话',
            'product_qq' => '商家 Qq',
            'product_features' => '产品特色',
            'product_desc' => '产品描述',
            'product_type' => '产品类型',
            'up_time' => '上架时间',
            'product_status' => '产品状态',
            'apply_conditions' => '申请条件',
            'min_credit' => '最小贷额度',
            'max_credit' => '最大贷额度',
            'rate_type' => '利率类型',
            'rate_num' => '利率百分比',
            'min_credit_days' => '借款期限下限',
            'max_credit_days' => '借款期限上限',
            'credit_limit_type' => '借款期限类型',
            'avg_credit_days' => '平均借款期限',
            'avg_credit_limit_type' => '平均借款期限类型',
            'fast_loan' => '最快放款时间',
            'fast_loan_type' => '最快放款时间类型',
            'url' => '地址',
            'logo_url' => 'logo',
            'apply_materia' => '申请所需材料',
            'credit_base' => '借款人基数',
            'tag_id' => '展示标签',
            'uv_limit' => 'UV控量',
            'sort' => '排序',
            'is_inner' => '是否内部产品',
            'is_valid' => '是否有效',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'tagIds' => '筛选标签',
            'creditLines' =>'贷款额度',
            'rate' =>'利率'
        ];
    }
//    public function setUp_time($value){
//        if($this->getScenario() === self::SCENARIO_BACKEND_CREATE_UPDATE){
//            $this->up_time = strtotime($value);
//        }
//    }
//    public function getUp_time($value){
//        return date('Y-m-d H:i:s',$value);
//    }

    public function getProductAssocTag()
    {
        return $this->hasMany(ProductAssocTag::class, ['product_id' => 'id']);
    }

    public function getTagIds()
    {
        return $this->getProductAssocTag()->select('tag_id')->column();
//        $sql = $this->getProductAsocTag()->select('tag_id')->createCommand()->getRawSql();
    }

    public function getTags()
    {
        return $this->hasMany(ProductTag::class, ['id' => 'tag_id'])
            ->via('productAssocTag');
    }
}
