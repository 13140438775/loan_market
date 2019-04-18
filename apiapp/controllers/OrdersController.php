<?php

namespace app\controllers;

use Yii;
use app\services\OrdersService;
use common\exceptions\RequestException;
use common\models\Orders;

/**
 * Class BaseController
 * @package api\controllers
 */
class OrdersController extends BaseController
{
    /**
     * 个人中心信息查询
     * @return mixed
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $data              = OrdersService::getInstance()->getUserCenter(\Yii::$app->user->getIdentity()['id']);
        $data['userPhone'] = substr_replace(\Yii::$app->user->getIdentity(false)['user_phone'], '****', 3, 4);

        return $data;
    }

    /**
     * actionOrderList 订单列表
     * @date     2019-03-12 11:42:47
     * @author   周晓坤<1426801685@qq.com>
     * @return array
     * @throws RequestException
     * @throws \Throwable
     * @throws \common\exceptions\OrdersException
     */
    public function actionOrderList()
    {
        $user        = Yii::$app->user->getIdentity();
        $queryParams = Yii::$app->request->get();
        $status      = $queryParams['status'];
        $page        = $queryParams['page'] ?? 1;
        $page_num    = $queryParams['page_num'] ?? 20;

        if (!in_array($status, array_keys(OrdersService::$frontOrderStatus))) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }
        return OrdersService::orderList($user['id'], $status, $page, $page_num);
    }

    /**
     * actionOrderDetail 订单详情
     * @date     2019-03-12 11:59:00
     * @author   周晓坤<1426801685@qq.com>
     * @return array|\yii\db\ActiveRecord|null
     * @throws \Throwable
     * @throws \common\exceptions\OrdersException
     */
    public function actionOrderDetail()
    {
        $order_sn = Yii::$app->request->get('order_sn');
        $user     = Yii::$app->user->getIdentity();
        return OrdersService::orderDetail($user['id'], $order_sn);
    }

    /**
     * actionRepayPlanDetail 订单还款计划详情
     * @date     2019/3/19 10:43
     * @author   周晓坤<1426801685@qq.com>
     * @return mixed
     * @throws \Throwable
     */
    public function actionRepayPlanDetail()
    {
        $user          = Yii::$app->user->getIdentity();
        $order_id      = Yii::$app->request->get('order_id');
        $repay_plan_id = Yii::$app->request->get('repay_plan_id');
        return OrdersService::repayPlanDetail($user['id'], $order_id, $repay_plan_id);
    }

    /**
     * actionRepayPlanItemDetail 还款计划子项详情
     * @date     2019/3/19 10:44
     * @author   周晓坤<1426801685@qq.com>
     * @return mixed
     * @throws \Throwable
     */
    public function actionRepayPlanItemDetail()
    {
        $user               = Yii::$app->user->getIdentity();
        $repay_plan_item_id = Yii::$app->request->get('repay_plan_item_id');
        return OrdersService::repayPlanItemDetail($user['id'], $repay_plan_item_id);
    }

    /**
     * actionToSignUp 签约
     * @date     2019/3/21 15:22
     * @author   周晓坤<1426801685@qq.com>
     * @return bool
     * @throws \Throwable
     */
    public function actionToSignUp()
    {
        $user = Yii::$app->user->getIdentity();
        $order_sn = Yii::$app->request->get('order_sn');
        return OrdersService::toSignUp($user['id'], $order_sn);
    }
}
