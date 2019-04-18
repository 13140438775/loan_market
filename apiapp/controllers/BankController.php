<?php

namespace app\controllers;


use app\services\BankService;
use common\helpers\Helper;
use common\services\CommonMethodsService;

/**
 * Class BaseController
 * @package api\controllers
 */
class BankController extends BaseController
{
    // 是否有待绑卡的订单号
    public function actionUnbindCardOrder()
    {
        $product_id = \Yii::$app->request->get("product_id");
        return BankService::getUnbindCardOrder($product_id);
    }

    // 用户信息
    public function actionUserInfo()
    {
        return BankService::userInfo();
    }

    // 绑卡页面接口
    public function actionBindCardPage()
    {
        $product_id = \Yii::$app->request->get("product_id");
        return BankService::bindCardPage($product_id);
    }

    // 用户卡列表
    public function actionUserCards()
    {
        $product_id = \Yii::$app->request->get("product_id");
        return BankService::userProductCard($product_id);
    }

    // 支持银行卡列表
    public function actionGetBankList()
    {
        $product_id = \Yii::$app->request->get("product_id", "1");
        $card_type = \Yii::$app->request->get("card_type");
        $params = [
            'card_type' => $card_type
        ];
        return CommonMethodsService::openApiCurl($product_id, "getBankList", "POST", $params);
    }

    // 获取cardBin
    public function actionGetBankName()
    {
        $card_number = \Yii::$app->request->get("card_number");
        $data = [
            "cardNo" => $card_number
        ];
        return Helper::apiCurl(Helper::getApiUrl("cardBin"), "POST", $data, [], 'json');
    }

    // 订单绑定银行卡
    public function actionBindCard()
    {
        $order_sn = \Yii::$app->request->post("order_sn");
        $bank_name = \Yii::$app->request->post("bank_name");
        $user_name = \Yii::$app->request->post("user_name");
        $user_idcard = \Yii::$app->request->post("user_idcard");
        $card_number = \Yii::$app->request->post("card_number");
        $card_phone = \Yii::$app->request->post("card_phone");
        $user_phone = \Yii::$app->request->post("user_phone");
        $verify_code = \Yii::$app->request->post("verify_code");
        $card_type = \Yii::$app->request->post("card_type", 2);
        $product_id = \Yii::$app->request->post("product_id");
        $use_type = \Yii::$app->request->post("use_type");

        $bank_code = isset(BankService::$bank_code_set[$bank_name]['bank_code']) ? BankService::$bank_code_set[$bank_name]['bank_code']: "";
        $bank_icon = isset(BankService::$bank_code_set[$bank_name]['bank_icon']) ? BankService::$bank_code_set[$bank_name]['bank_icon']: "";
        $params = [
            'order_sn' => $order_sn,
            'bank_code' => $bank_code,
            'user_name' => $user_name,
            'user_idcard' => $user_idcard,
            'card_number' => $card_number,
            'card_phone' => $card_phone,
            'user_phone' => $user_phone,
            'verify_code' => $verify_code,
            'card_type' => $card_type
        ];

        // TODO 待调试
        $data = [
            'bankCode' => $bank_code,
            'bankIcon' => $bank_icon,
            'bankName' => $bank_name,
            'cardNum' => $card_number
        ];
        BankService::bindCard($product_id, $params, $data, $use_type);
    }


    // H5绑卡同步回调
    public function actionH5BindCardCallback()
    {
        $order_sn = \Yii::$app->request->get("order_sn");
        $bind_status = \Yii::$app->request->get("bind_status");
        return BankService::h5BindCardCallback($order_sn, $bind_status);
    }
}
