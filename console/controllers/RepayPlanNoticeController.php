<?php

namespace console\controllers;

use common\services\RepayPlanNoticeService;
use yii\console\Controller;

class RepayPlanNoticeController extends Controller
{
    /**
     * 文件描述 用户距离还款T-3日短信提醒
     * Created On 2019-03-14 11:46
     * Created By heyafei
     */
    public function actionOrderNoticeT_3()
    {
        try{
            $type = "-3";
            RepayPlanNoticeService::repayPlanNotice($type);
            echo date("Y-m-d H:i:s")."\t OrderNoticeT_3 \t Success \n";
        } catch(\Exception $e) {
            echo date("Y-m-d H:i:s")."\t OrderNoticeT_3 \t Fail \n";
            echo "********************************************* \n";
            print_r($e->getMessage());
            echo "\n ********************************************* \n";
        }
    }

    /**
     * 文件描述 用户距离还款T-1日短信提醒
     * Created On 2019-03-14 11:46
     * Created By heyafei
     */
    public function actionOrderNoticeT_1()
    {
        try{
            $type = "-1";
            RepayPlanNoticeService::repayPlanNotice($type);
            echo date("Y-m-d H:i:s")."\t OrderNoticeT_1 \t Success \n";
        } catch(\Exception $e) {
            echo date("Y-m-d H:i:s")."\t OrderNoticeT_1 \t Fail \n";
            echo "********************************************* \n";
            print_r($e->getMessage());
            echo "\n ********************************************* \n";
        }
    }

    /**
     * 文件描述 用户距离还款T日短信提醒
     * Created On 2019-03-14 11:46
     * Created By heyafei
     */
    public function actionOrderNoticeT()
    {
        try{
            $type = "0";
            RepayPlanNoticeService::repayPlanNotice($type);
            echo date("Y-m-d H:i:s")."\t OrderNoticeT \t Success \n";
        } catch(\Exception $e) {
            echo date("Y-m-d H:i:s")."\t OrderNoticeT \t Fail \n";
            echo "********************************************* \n";
            print_r($e->getMessage());
            echo "\n ********************************************* \n";
        }
    }

    /**
     * 文件描述 用户距离还款T+1日短信提醒
     * Created On 2019-03-14 11:46
     * Created By heyafei
     */
    public function actionOrderNoticeT1()
    {
        try{
            $type = "1";
            RepayPlanNoticeService::repayPlanNotice($type);
            echo date("Y-m-d H:i:s")."\t OrderNoticeT1 \t Success \n";
        } catch(\Exception $e) {
            echo date("Y-m-d H:i:s")."\t OrderNoticeT1 \t Fail \n";
            echo "********************************************* \n";
            print_r($e->getMessage());
            echo "\n ********************************************* \n";
        }
    }

    /**
     * 文件描述 用户距离还款T+3日短信提醒
     * Created On 2019-03-14 11:46
     * Created By heyafei
     */
    public function actionOrderNoticeT3()
    {
        try{
            $type = "3";
            RepayPlanNoticeService::repayPlanNotice($type);
            echo date("Y-m-d H:i:s")."\t OrderNoticeT3 \t Success \n";
        } catch(\Exception $e) {
            echo date("Y-m-d H:i:s")."\t OrderNoticeT3 \t Fail \n";
            echo "********************************************* \n";
            print_r($e->getMessage());
            echo "\n ********************************************* \n";
        }
    }
}
