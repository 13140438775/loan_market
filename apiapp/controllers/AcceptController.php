<?php

namespace app\controllers;


use app\services\AcceptService;
use common\exceptions\ProductException;
use common\exceptions\BaseException;
use common\services\UvPvService;

trait AcceptController
{

    public function actionView()
    {
        try {
            $productId = \Yii::$app->request->get('id', "0");

            $acceptService = AcceptService::getInstance();
            list($product, $property) = $acceptService->getProductDetail($productId);

            $uvPvService = new UvPvService();
            $product['enable_count_limit'] += $uvPvService->getProductUv($productId);

            //机构过滤用户
            $userAccept = $acceptService->isUserAccept($productId);
            //金额判断
            $nowAccept = $acceptService->validateUserAccept($userAccept, $product);

            $accept['amount'] = end($nowAccept['accept_amounts']);
            $accept['term'] = end($nowAccept['accept_terms']);
            $accept['term_type'] = $nowAccept['accept_term_type'];

            //试算金额
            $loanCalculate = $acceptService->getLoanCalculate($accept['amount'], $accept['term'], $accept['term_type'], $productId);

            //认证资料状态，并检测是否符合一推
            $productAuth = $acceptService->checkProductAuth($product, $accept);

            $data = [
                'product' => $product,
                'caluculate' => $loanCalculate,
                'auth' => $productAuth,
                'property' => $property,
                'accept' => $nowAccept,
            ];

            return $data;
        }catch (ProductException $e){
            //资质不符或额度不足
            if($e->getCode() == ProductException::NOT_LOGIN){
                throw new ProductException($e->getCode(),$e->getMessage());
            }elseif($e->getCode() == ProductException::INVALID_USER || $e->getCode() == ProductException::INVALID_VIEW){
                if(!empty($product) && !empty($property)){
                    $errorData = ['product' => $product,'property' => $property];
                }
                return [
                    'code' =>  $e->getCode(),
                    'msg' =>  ProductException::getReason($e->getCode()),
                    'data' => empty($errorData)?json_decode($e->getMessage(),true):$errorData,
                ];
            }elseif($e->getCode() == ProductException::HAVE_ORDER){
                //已有订单，跳转订单详情页
                return [
                    'code' =>  $e->getCode(),
                    'msg' =>  ProductException::getReason($e->getCode()),
                    'data' => json_decode($e->getMessage(),true)['order_sn'],
                ];
            }
        }catch (BaseException $e){
            throw new BaseException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 试算接口
     * @return mixed
     * @throws \Throwable
     */
    public function actionLoanCalculate(){

        $maxAmount = \Yii::$app->request->post('amount');
        $maxTerm = \Yii::$app->request->post('term');
        $termType = \Yii::$app->request->post('type');
        $productId = \Yii::$app->request->post('product_id');

        $userInfo = \Yii::$app->user->getIdentity();

        return  AcceptService::getInstance()->getLoanCalculate($maxAmount,$maxTerm,$termType,$productId,$userInfo['real_name'],$userInfo['user_phone'],$userInfo['card_id']);
    }

    /**
     * 获取产品合同
     * @return mixed
     * @throws BaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionOrderContracts(){
        $productId = \Yii::$app->request->get('id', "0");
        return AcceptService::getInstance()->getOrderContracts($productId);
    }

    /**
     * 申请借款
     * @return bool
     */
    public function actionPushUserAdd(){
        $maxAmount = \Yii::$app->request->post('amount');
        $maxTerm = \Yii::$app->request->post('term');
        $termType = \Yii::$app->request->post('type');
        $productId = \Yii::$app->request->post('product_id');

        AcceptService::getInstance()->checkPushUserAdd($maxAmount,$maxTerm,$termType,$productId);
        return true;
    }
}