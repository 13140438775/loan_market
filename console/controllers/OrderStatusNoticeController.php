<?php

namespace console\controllers;

use common\services\OrderStatusNoticeService;
use yii\console\Controller;

class OrderStatusNoticeController extends Controller
{
    /**
     * 文件描述 异步发送消息提醒（从左边入队，右边出对），数据入队格式
     * Created On 2019-03-14 11:46
     * Created By heyafei
     */
    public function actionOrderStatusNotice()
    {
        try{
            $service = \Yii::$container->get("OrderStatusNoticeService");
            /**
             * @var OrderStatusNoticeService $service
             */
            $service->loopNotice();
            echo date("Y-m-d H:i:s")."\t Success \n";
        } catch(\Exception $e) {
            echo date("Y-m-d H:i:s")."\t Fail \n";
            echo "********************************************* \n";
            print_r($e->getMessage());
            echo "\n ********************************************* \n";
        }
    }
}
