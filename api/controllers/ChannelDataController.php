<?php
namespace api\controllers;


use common\exceptions\RequestException;
use common\services\StatsChannelDataService;

/**
 * Site controller
 */
class ChannelDataController extends BaseController
{
    /**
     * 文件描述 统计uv/ip/pv
     * Created On 2019-03-04 14:57
     * Created By heyafei
     * @throws RequestException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionStatsChannelData()
    {
        $channel_id = \Yii::$app->request->get("channel_id");
        if(empty($channel_id)) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }
        $ip = isset($_COOKIE['REMOTE_ADDR']) ? $_COOKIE['REMOTE_ADDR']: "0.0.0.0";
        if(isset($_COOKIE['unique_user']) && $_COOKIE['unique_user']) {
            $unique_user = $_COOKIE['unique_user'];
        } else {
            $length = 12;
            $bytes = openssl_random_pseudo_bytes($length);
            $key = strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
            $unique_user = $key . $channel_id;
            setcookie("unique_user", $unique_user);
        }
        /**
         * @var $service StatsChannelDataService
         */
        $service = \Yii::$container->get("StatsChannelDataService");
        $service->setChannelRedis($channel_id, $unique_user, $ip);
        return;
    }
}
