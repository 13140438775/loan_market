<?php

namespace open\controllers;

use Yii;
use open\services\OrderService;
use common\services\RepayPlanFeedbackService;

class OrderController extends BaseController
{
    /**
     * actionLendingFeedback 订单放款结果回调
     * @date     2019/3/14 11:31
     * @author   周晓坤<1426801685@qq.com>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \open\exceptions\OrderException
     */
    public function actionLendingFeedback()
    {
        $data = Yii::$app->request->post('args');
        return OrderService::lendingFeedback($data);
    }

    /**
     * actionApproveFeedback 订单审批结果回调
     * @date     2019/3/14 11:38
     * @author   周晓坤<1426801685@qq.com>
     * @throws \open\exceptions\OrderException
     */
    public function actionApproveFeedback()
    {
        $data = Yii::$app->request->post('args');
        return OrderService::approveFeedback($data);
    }

    /**
     * actionRepayPlanFeedback 还款计划回调
     * @date     2019/3/14 11:53
     * @author   周晓坤<1426801685@qq.com>
     * @throws \common\exceptions\RepayPlanFeedbackException
     */
    public function actionRepayPlanFeedback()
    {
        $data = Yii::$app->request->post('args');
        return RepayPlanFeedbackService::repayPlanFeedback($data);
    }

    /**
     * 文件描述 还款结果回调
     * Created On 2019-03-15 20:54
     * Created By heyafei
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionRepayStatusFeedback()
    {
        $args = Yii::$app->request->post('args');
        return OrderService::repayStatusFeedback($args);
    }

    /**
     * 文件描述 绑卡结果回调（H5绑卡需要）
     * Created On 2019-03-18 21:42
     * Created By heyafei
     * @throws \Throwable
     */
    public function actionBindCardFeedback()
    {
        $args = \Yii::$app->request->post("args");
        OrderService::bindCardFeedback($args);
    }
}