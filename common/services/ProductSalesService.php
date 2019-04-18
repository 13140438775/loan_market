<?php

namespace common\services;

use common\models\ProductSales;

/**
 * 产品销量控制
 * Class ProductSalesService
 * @package app\services
 */
class ProductSalesService
{
    CONST FIRST = 'first';
    CONST SECOND = 'second';

    //插入类型
    public $saleType = [
        'first' => 'first_loan_one_push',
        'first_loan' => 'first_loan_approval',
        'second' =>'second_loan_one_push',
        'second_loan' =>'second_loan_approval',
    ];

    /**
     * 获取产品销量，不区分平台
     * @param $productId
     *
     * @return ProductSales|null
     */
    public function getSalesByPid($productId){
        $productSales = ProductSales::findOne(["product_id"=>$productId]);

        if(empty($productSales)){
            $productSales['first_loan_one_push'] = 0;
            $productSales['first_loan_approval'] = 0;
            $productSales['second_loan_one_push'] = 0;
            $productSales['second_loan_approval'] = 0;
        }

        return $productSales;
    }

    /**
     * 更新库存
     * @param $productId
     * @param $type
     * @param $platForm 平台
     * @param $addPlus  库存增减
     * @return bool
     */
    public function setSalesByPid($productId,$type,$addPlus = 1,$platForm = '1'){
        $fieldType = $this->saleType[$type];
        $productSalesModel = new ProductSales();
        $productSales = $productSalesModel::findOne(['product_id' => $productId, 'application' => $platForm]);

        if($productSales){
            $ret = ProductSales::updateAllCounters([$fieldType => $addPlus], ['product_id' => $productId, 'application' => $platForm]);
        }else{
            $productSales->product_id = $productId;
            $productSales->$fieldType = 1;
            $productSales->application = $platForm;
            $ret = $productSales->save();
        }

        return $ret;
    }
}

