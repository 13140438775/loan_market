<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/8
 * Time: 下午3:52
 */

namespace app\services\observer;

use common\exceptions\ProductException;
use common\helpers\Helper;
use common\models\ProductPlatLimit;
use common\services\ProductSalesService;
use common\models\Product;

class ProductObServer extends ObServer
{
    /**
     * 基于产品基础可见配置
     * @param $product
     *
     * @return bool
     * @throws ProductException
     */
    public function _isShow(Product $product){
        //B端是否上架
        if($product['config_status'] != self::OPEN){
            \Yii::info("产品{$product['id']}未开启B端上架");
            throw new ProductException(ProductException::INVALID_VIEW);
        }
        //运营是否上线
        if($product['display_status'] == self::CLOSE){
            \Yii::info("产品{$product['id']}未开启运营上线");
            throw new ProductException(ProductException::INVALID_VIEW);
        }
        return true;
    }

    public function _filter(Product $product){
        //是否开启限量配置，不开启直接拒绝显示
        if($product['enable_count_limit'] == self::CLOSE){
            throw new ProductException(ProductException::INVALID_VIEW);
        }

        //是否开启分时段，单日控量
        if($product['is_time_sharing'] == self::OPEN){
            $date = date('Hi');
            if ($date <= $product['limit_begin_time'] || $date >= $product['limit_end_time']) {
                \Yii::error("产品：{$product['id']}|| 开启分时段过滤");
                throw new ProductException(ProductException::INVALID_VIEW);
            }
        }

        //uv_day_limit 和 一二推控量 不可都为空，二选一判断
        if($product['uv_day_limit'] <= 0 ) {
            $productSalesList = (new ProductSalesService())->getSalesByPid($product['id']);

            //是否区分平台
            if($product['is_diff_plat'] == self::OPEN){
                $this->diffPlat($productSalesList,$product);
            }else{
                $productSales['first_loan_one_push'] = array_sum(array_column($productSalesList, 'first_loan_one_push'));
                $productSales['first_loan_approval'] = array_sum(array_column($productSalesList, 'first_loan_approval'));
                $productSales['second_loan_one_push'] = array_sum(array_column($productSalesList, 'second_loan_one_push'));
                $productSales['second_loan_approval'] = array_sum(array_column($productSalesList, 'second_loan_approval'));

                $this->allPlat($productSales,$product);
            }
        }

//      H5在中间页面判断，这块逻辑可省略
//        else{
//            // H5统计uv_day_limit,
//            if($product['uv_day_limit'] > 100){
//                throw new ProductException(ProductException::INVALID_VIEW);
//            }
//
//        }


    }

    /**
     * 不同平台合并计算
     * @param $productSalesList
     * @param $product
     * TODO 待测试
     * @throws ProductException
     */
    private function diffPlat($productSalesList,$product){
        $productPlatLimit = ProductPlatLimit::findAll(['product_id'=>$product['id']])->asArray();
        $productPlatLimitKey = Helper::mapByKey($productPlatLimit,'app_id');
        $productSalesGroup = Helper::groupByKey($productSalesList,'application');
        $productSales = [];
        foreach ($productSalesGroup as $key => $val){
            if(isset($productPlatLimitKey[$key])){
                $productSales['first_loan_one_push'] += array_sum(array_column($productSalesGroup[$key], 'first_loan_one_push'));
                $productSales['first_loan_approval'] += array_sum(array_column($productSalesGroup[$key], 'first_loan_approval'));
                $productSales['second_loan_one_push'] += array_sum(array_column($productSalesGroup[$key], 'second_loan_one_push'));
                $productSales['second_loan_approval'] += array_sum(array_column($productSalesGroup[$key], 'second_loan_approval'));
            }
        }
        //合并平台单量判断是否过期
        $this->allPlat($productSales,$product);
    }

    /**
     * 单平台判断是否超量
     * @param $productSales
     * @param $product
     *
     * @throws ProductException
     */
    private function allPlat($productSales,$product){

        //是否区分首复贷
        if($product['is_diff_first'] == self::OPEN){
            // 首贷一推判断，判断订单uid不重复
            if(!empty($product['first_loan_one_push_limit'])){
                if($product['first_loan_one_push_limit'] <= $productSales['first_loan_one_push']){
                    \Yii::error("产品：{$product['id']}||首贷一推判断超过总数");
                    throw new ProductException(ProductException::INVALID_VIEW);
                }
            }

            //首贷审核单
            if(!empty($product['first_loan_approval_limit'])){
                if($product['first_loan_approval_limit'] <= $productSales['first_loan_approval']){
                    \Yii::error("产品：{$product['id']}||首贷审核单超过总数");
                    throw new ProductException(ProductException::INVALID_VIEW);
                }
            }

            //复贷一推控制
            if(!empty($product['second_loan_one_push_limit'])){
                if($product['second_loan_one_push_limit'] <= $productSales['second_loan_one_push']){
                    \Yii::error("产品：{$product['id']}||复贷一推控制超过总数");
                    throw new ProductException(ProductException::INVALID_VIEW);
                }
            }
            //复贷审核单单控制
            if(!empty($product['second_loan_approval_limit'])){
                if($product['second_loan_approval_limit'] <= $productSales['second_loan_approval']){
                    \Yii::error("产品：{$product['id']}||复贷审核单量超过总数");
                    throw new ProductException(ProductException::INVALID_VIEW);
                }
            }
        }else{
            //判断订单总量,一推单量
            if(!empty($product['first_loan_one_push_limit'])){
                if($product['first_loan_one_push_limit'] <= $productSales['first_loan_one_push'] + $productSales['second_loan_one_push']){
                    \Yii::error("产品：{$product['id']}||一推单量超过总数");
                    throw new ProductException(ProductException::INVALID_VIEW);
                }
            }

            //判断订单总量,审核单量
            if(!empty($product['first_loan_approval_limit'])){
                if($product['first_loan_approval_limit'] <= $productSales['first_loan_approval'] + $productSales['second_loan_approval']){
                    \Yii::error("产品：{$product['id']}||审核单量超过总数");
                    throw new ProductException(ProductException::INVALID_VIEW);
                }
            }
        }
    }
}