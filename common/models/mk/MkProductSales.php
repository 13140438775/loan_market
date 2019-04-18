<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/11
 * Time: 上午10:53
 */

namespace common\models\mk;


class MkProductSales extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_sales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'first_loan_one_push', 'first_loan_approval', 'second_loan_one_push', 'second_loan_approval', 'application'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'first_loan_one_push' => 'First Loan One Push',
            'first_loan_approval' => 'First Loan Approval',
            'second_loan_one_push' => 'Second Loan One Push',
            'second_loan_approval' => 'Second Loan Approval',
            'application' => 'Application',
        ];
    }
}