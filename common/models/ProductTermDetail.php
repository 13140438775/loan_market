<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019-03-12
 * Time: 21:59
 */

namespace common\models;


use common\models\mk\MkProductTermDetail;

class ProductTermDetail extends MkProductTermDetail
{

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '产品期限详情表',
            'sum_time' => '期限',
            'sum_time_unit' => '时间单位 1 日 2 月 3 年',
            'term_time' => '每期时长',
            'term_time_unit' => '每期时长单位',
            'term_apr' => '利率',
            'term_fee' => '费率',
            'product_id' => 'mk_product表主键id',
        ];
    }
}