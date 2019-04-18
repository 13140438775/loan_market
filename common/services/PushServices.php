<?php

namespace common\services;

use Yii;

class PushService {
    /**
     * 数据格式
     * {
     *    "app":"commaApp",       // 应用
     *    "registration_ids":[],   // 推送设备ids
     *    "alert":"xxx",          // 推送标题
     *    "push_data":{            // 推送数据
     *      "direct_type": "h5",   // 枚举值（native ／ h5）
     *      "direct": "default",  // 命令字
     *      "data": {}            // 业务数据
     *    }
     * }
     * 文件描述 推送回调
     * Created On 2019-03-06 15:26
     * Created By heyafei
     * @param $bodyParams
     */
    public static function pushCallback($bodyParams){
        $bodyParams['pushData']['alert'] = $bodyParams['alert'];
        \Yii::$app->Jg->setClient($bodyParams['app'])
            ->push($bodyParams['registration_ids'], $bodyParams['alert'], $bodyParams['push_data']);
    }
}