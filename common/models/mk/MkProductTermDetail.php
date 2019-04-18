<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_term_detail".
 *
 * @property int $id 产品期限详情表
 * @property int $sum_time 期限
 * @property int $amount 金额
 * @property int $sum_time_unit 时间单位 1 日 2 月 3 年
 * @property int $term_time 每期时长
 * @property int $term_time_unit 每期时长单位1 日 2 月 3 年
 * @property int $term_apr 利率
 * @property int $term_fee 费率
 * @property int $product_id mk_product表主键id
 */
class MkProductTermDetail extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_term_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sum_time', 'amount', 'sum_time_unit', 'term_time', 'term_time_unit', 'term_fee', 'product_id'], 'required'],
            [['sum_time', 'amount', 'sum_time_unit', 'term_time', 'term_time_unit', 'term_apr', 'term_fee', 'product_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品期限详情表',
            'sum_time' => '期限',
            'amount' => '金额',
            'sum_time_unit' => '时间单位 1 日 2 月 3 年',
            'term_time' => '每期时长',
            'term_time_unit' => '每期时长单位1 日 2 月 3 年',
            'term_apr' => '利率',
            'term_fee' => '费率',
            'product_id' => 'mk_product表主键id',
        ];
    }
}
