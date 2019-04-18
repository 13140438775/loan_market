<?php

namespace common\services;

class ProductRedisListService
{
    public $redis;

    const REDIS_PREFIX = "loan_market";

    static $product_home = 1;

    public function __construct()
    {
        $this->redis = \Yii::$app->redis;
    }

    private function getUserKey()
    {
        return self::REDIS_PREFIX . "_user_id";
    }

    private function getProductKey($user_id)
    {
        return self::REDIS_PREFIX . "_product_weight_" . $user_id;
    }

    /**
     * 文件描述 消耗队列
     */
    public function removeLast()
    {
        $lists_num = $this->redis->lLen($this->getUserKey());
        if($lists_num) {
            $user_id = $this->redis->rPop($this->getUserKey());
            $this->getProductWeight($user_id);
        }

    }

    /**
     * 文件描述 获取某用户的产品列表权重
     * * @param $user_id
     */
    public function getProductWeight($user_id)
    {
        $product_weight = ProductService::productList(); // 查询用户所有的产品
        $product_weight = $this->setProductWeight($product_weight, $user_id);
        $this->redis->hMset($this->getProductKey($user_id), $product_weight);
    }

    /**
     * 文件描述 设置产品的权重
     * @param $product_weight
     * @param $user_id
     * @return array
     */
    public function setProductWeight($product_weight, $user_id)
    {
        $product_list = [];
        foreach ($product_weight AS $product_id => $weight) {
            // 复贷置顶
            $ratio = ProductService::isAgainProduct($user_id, $product_id);

            // 据还款N天
            if($ratio == self::$product_home) {
                $ratio = ProductService::isRefundProduct($user_id, $product_id);
            }

            // 在贷产品
            if($ratio == self::$product_home) {
                $ratio = ProductService::isLoaningProduct($user_id, $product_id);
            }

            // 额度为空
            if($ratio == self::$product_home) {
                $ratio = ProductService::isLimitProduct($user_id, $product_id);
            }

            // 资质不符合(客群筛选)
            if($ratio == self::$product_home) {
                $ratio = ProductService::isAptitudeProduct($user_id, $product_id);
            }

            // 不准入
            if($ratio == self::$product_home) {
                $ratio = ProductService::isAccessProduct($user_id, $product_id);
            }

            // 被拒
            if($ratio == self::$product_home) {
                $ratio = ProductService::isRefusedProduct($user_id, $product_id);
            }
            $product_list[$product_id] = $weight * $ratio;
        }
        return $product_list;
    }
}

