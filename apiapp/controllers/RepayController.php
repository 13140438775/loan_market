<?php

namespace app\controllers;

use app\services\RepayService;
use common\services\CommonMethodsService;

/**
 * Class BaseController
 * @package api\controllers
 */
class RepayController extends BaseController
{

    // 判断订单是否满足还款的条件
    public function actionRepayOrders()
    {
        $repay_plan_id = \Yii::$app->request->get("repay_plan_id"); // 还款计划ID
        $repay_plan_item_id = \Yii::$app->request->get("repay_pan_item_id"); // 子还款计划ID
        return RepayService::repayOrders($repay_plan_id, $repay_plan_item_id);
    }

    // 还款页面接口
    public function actionRepayPage()
    {
        $order_sn = \Yii::$app->request->post("order_sn");
        $amount = \Yii::$app->request->post("amount");
        $repay_periods = \Yii::$app->request->post("repay_periods", []);
        return RepayService::repayPage($order_sn, $repay_periods, $amount);
    }

    /**
     * 文件描述 订单还款接口
     * Created On 2019-03-16 19:52
     * Created By heyafei
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     * @throws \common\exceptions\ProductException
     */
    public function actionApplyRepay()
    {
        $product_id    = \Yii::$app->request->post("product_id");
        $order_sn      = \Yii::$app->request->post("order_sn");
        $verify_code   = \Yii::$app->request->post("verify_code", "");
        $repay_periods = \Yii::$app->request->post("repay_periods", []);
        $data          = [
            'order_sn'      => $order_sn,
            'verify_code'   => $verify_code,
            'repay_periods' => $repay_periods,
        ];
        return CommonMethodsService::openApiCurl($product_id, "applyRepay", "POST", $data);
    }
}
