<?php

namespace console\controllers;
use common\services\StatsChannelDataService;
use yii\console\Controller;

class StatsChannelDataController extends Controller
{
    /**
     * 文件描述 每天统计登陆/注册数据落地 每天凌晨3点落地
     * Created On 2019-02-20 18:20
     * Created By heyafei
     * @param $date
     */
    public function actionStatsChannelData($date)
    {
        $date = $date ? $date: date("Y-m-d", time() - 60 * 60 * 24);
        try{
            StatsChannelDataService::saveChannelData($date);
            echo date('Y-m-d H:i:s')."\tSuccess \n";
        } catch (\Exception $e){
            echo date('Y-m-d H:i:s')."\tFail \n";
            echo "**************************************************************\n";
            print_r($e->getMessage());
            echo "**************************************************************\n";
        }
    }

    /**
     * 文件描述 每天统计UV/PV/IP数据落地 每天凌晨4点落地
     * Created On 2019-02-25 11:23
     * Created By heyafei
     * @param $date
     */
    public function actionSaveChannelRedisData($date)
    {
        $date = $date ? $date: date("Ymd", time() - 60 * 60 *24);
        try {
            $service = \Yii::$container->get("StatsChannelDataService");
            /**
             * @var $service StatsChannelDataService
             */
            $service->getChannelIds($date);
            echo date('Y-m-d H:i:s')."\tSuccess \n";

        } catch(\Exception $e) {
            echo date('Y-m-d H:i:s')."\tFail \n";
            echo "**************************************************************\n";
            print_r($e->getMessage());
            echo "**************************************************************\n";
        }
    }
}
