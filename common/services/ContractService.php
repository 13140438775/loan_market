<?php

namespace common\services;

/**
 * ContractService 合同协议服务
 */
class ContractService 
{
    
    /**
     * 获取机构合同
     * @param        $productId
     * @param string $orderSn
     *
     * @return mixed
     * @throws ProductException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     */
    public static function getOrderContracts($productId,$orderSn = ''){

        $data = [];
        if(!empty($orderSn)) {
            $data['order_sn'] = $orderSn;
        }
        $response = CommonMethodsService::openApiCurl($productId, "getContracts", "POST",$data);

        return $response['response'];
    }
}