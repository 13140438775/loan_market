<?php

namespace console\controllers;
use common\services\DataCollectService;
use yii\console\Controller;
use Yii;
class DataStatisticsController extends Controller
{
    /**
     * 每天取昨天redis的统计数据 落地数据库
     * actionYesterdayStore
     * @date     2019/1/30 3:23 PM
     * @author   Wei Yang<suncode_666@163.com>
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionTodayStore(){
        $date = date('Ymd', time() - 30);
//        $date = date('Ymd');
        /** @var DataCollectService $service */
        $service = Yii::$container->get('DataCollectService');
        try{
            $service->saveSomeDayDataToDb($date);
            echo date('Y-m-d H:i:s')."\tSuccess \n";
        }catch (\Exception $e){
            echo date('Y-m-d H:i:s')."\tFail \n";
            echo "**************************************************************\n";
            print_r($e->getMessage());
            echo "**************************************************************\n";
        }

    }

}
