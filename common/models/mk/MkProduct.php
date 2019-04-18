<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product".
 *
 * @property int $id 产品表
 * @property string $name 产品名称
 * @property int $merchant_id 所属公司id merchant主键id
 * @property string $show_name 展示名称
 * @property string $logo_url logo地址
 * @property string $description 产品简介
 * @property int $sort_min_loan_time (排序)最快放款时间(统一转化分钟)
 * @property int $sort_min_loan_time_type (排序)最快放款时间单位1分2小时3天
 * @property string $show_min_loan_time (展示)最快放款时间
 * @property string $show_interest_desc (展示)息费说明
 * @property string $show_amount_range (展示)额度范围
 * @property int $max_amount 最高实际额度范围
 * @property int $min_amount 最低实际额度范围
 * @property int $interest_day (排序)实际日息%存整形除以100
 * @property string $show_avg_term (展示)期限范围
 * @property int $interest_pay_type 息费收取方式1 放款时扣息2 先息后本 3 等额本息 4 到期还本息  5 其他
 * @property string $interest_pay_type_desc 其他息费方式说明 type为5
 * @property int $show_tag_id 展示标签
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $call_type 调用方式0 api 1 h5
 * @property int $product_type api 产品属性1单期 2 多期
 * @property int $is_fixed_step api 是否是固定期限粒度1固定期限粒度 2非固定期限粒度
 * @property int $incr_step api固定期限粒度步长
 * @property int $incr_amount_step api 金额粒度步长(分)
 * @property int $is_same_interest api 是否统一费率 1是 0否
 * @property int $term_type api 期限范围 1 日 2 月 3 年
 * @property int $min_term api 最低期限
 * @property int $max_term api 最高期限
 * @property int $single_interest api 每期利率
 * @property int $single_fee api 每期费率
 * @property int $last_operator_id 上次操作人id
 * @property int $weight 权重
 * @property int $filter_user_enable 是否启用客群筛选0 否1 启用
 * @property int $enable_mobile_black 是否启用手机黑名单0 否1启用
 * @property int $min_age 年龄下限
 * @property int $max_age 年龄上限
 * @property string $area_filter 地域过滤 身份证前三位或者前6位
 * @property int $filter_net_time 手机过滤时长 (位操作) 1000000000 10位第一位占位 9位有效位 选中1 反之0
 * @property int $online_scenario 场景配置(位操作) 100000000 10位 9位有效位 第一位占位 第二位 是的首页大卡未 第二位是首页小卡位 第三位是贷款大全 第四位 被拒推荐
 * @property int $visible 可见逻辑(位操作) 100000000 第一位占位 第二位老客户可见 第二位 新客户可见 第三位复贷可见 第四位首贷可见
 * @property int $visible_mobile 可见端(位操作)1000000000 第一位占位 第二位ios可见 第三位安卓可见
 * @property int $enable_count_limit 是否开始限量配置
 * @property int $is_time_sharing 是否分时段
 * @property int $limit_begin_time 放量限制开始时间
 * @property int $limit_end_time 放量限制结束时间
 * @property int $uv_day_limit uv单日控量
 * @property int $is_diff_first 是否区分首复贷控量
 * @property int $is_diff_plat 是否区分平台控量
 * @property int $first_loan_one_push_limit 首贷一推单量控制
 * @property int $first_loan_approval_limit 首贷审核单量控制
 * @property int $second_loan_one_push_limit 复贷一推单量控制
 * @property int $second_loan_approval_limit 复贷审核订单量控制
 * @property int $config_status 是否上架状态 0 配置中 1 B端下架 2B端下架
 * @property int $display_status 运营上下架0 下架 1 上架
 * @property int $is_career_auto 是否需要职业联动
 * @property int $show_try_calc api是否展示试算
 */
class MkProduct extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'merchant_id', 'logo_url', 'description', 'sort_min_loan_time', 'show_min_loan_time', 'show_interest_desc', 'show_amount_range', 'max_amount', 'min_amount', 'interest_day', 'interest_pay_type', 'interest_pay_type_desc', 'show_tag_id', 'created_at', 'updated_at', 'last_operator_id', 'visible'], 'required'],
            [['merchant_id', 'sort_min_loan_time', 'sort_min_loan_time_type', 'max_amount', 'min_amount', 'interest_day', 'interest_pay_type', 'show_tag_id', 'created_at', 'updated_at', 'call_type', 'product_type', 'is_fixed_step', 'incr_step', 'incr_amount_step', 'is_same_interest', 'term_type', 'min_term', 'max_term', 'single_interest', 'single_fee', 'last_operator_id', 'weight', 'filter_user_enable', 'enable_mobile_black', 'min_age', 'max_age', 'filter_net_time', 'online_scenario', 'visible', 'visible_mobile', 'enable_count_limit', 'is_time_sharing', 'limit_begin_time', 'limit_end_time', 'uv_day_limit', 'is_diff_first', 'is_diff_plat', 'first_loan_one_push_limit', 'first_loan_approval_limit', 'second_loan_one_push_limit', 'second_loan_approval_limit', 'config_status', 'display_status', 'is_career_auto', 'show_try_calc'], 'integer'],
            [['name', 'show_name', 'logo_url', 'description', 'show_min_loan_time', 'show_interest_desc', 'show_amount_range', 'show_avg_term', 'interest_pay_type_desc', 'area_filter'], 'string', 'max' => 255],
        ];
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
            'is_career_auto' => '是否需要职业联动',
            'show_try_calc' => 'api是否展示试算',
        ];
    }
}
