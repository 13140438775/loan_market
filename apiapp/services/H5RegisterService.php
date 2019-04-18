<?php

namespace app\services;

use common\exceptions\UserException;
use common\helpers\Helper;
use common\models\LoanUsers;
use common\models\ChannelPvLog;

class H5RegisterService extends BaseService
{
    const REDIS_PREFIX = "loan_market";
    const CAPTCHA_INTERVAL = 60 * 5;
    const TOKEN_EXPIRE = 30 * 60 * 60 * 24;
    const REGISTER_TEMPLATE = 104;

    public static function getSmsCaptchaKey($phone)
    {
        return self::REDIS_PREFIX . "_app_sms_captcha_" . $phone;
    }

    /**
     * 文件描述 检查是否注册
     * Created On 2019-02-15 21:16
     * Created By heyafei
     * @param $phone
     * @return bool
     */
    public static function checkRegister($phone)
    {
        $loan_user = LoanUsers::findOne(["user_phone" => $phone]);
        if($loan_user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 文件描述 发送短信
     * Created On 2019-03-21 23:00
     * Created By heyafei
     * @param $phone
     * @return array|null
     * @throws \Exception
     */
    public static function getCaptcha($phone)
    {
        $captcha = random_int(100000, 999999);

        // 发送短信
//        CommonMethodsService::javaSmsApiCurl(REGISTER_TEMPLATE, $captcha, $phone);

        // 存入redis
        \Yii::$app->redis->setex(self::getSmsCaptchaKey($phone), self::CAPTCHA_INTERVAL, $captcha);

        if (YII_ENV_DEV | YII_ENV_TEST){
            return ['captcha' => (string)$captcha];
        }
        return null;
    }

    /**
     * 文件描述 注册
     * @param $password
     * @param $phone
     * @param $userCaptcha
     * @param $channel_id
     * @param $merchant_id
     * @return mixed
     * @throws \Throwable
     */
    public static function saveRegister($password, $phone, $userCaptcha, $channel_id, $merchant_id)
    {
        // 判断是登陆还是注册
        if(self::checkRegister($phone)){
            throw new UserException(UserException::HAS_USER);
        }
        return \Yii::$app->db->transaction(function ($db) use ($password, $phone, $userCaptcha, $channel_id, $merchant_id){
            $captcha = \Yii::$app->redis->get(self::getSmsCaptchaKey($phone));
            // 验证码过期
            if(!$captcha){
                throw new UserException(UserException::EXPIRED_CAPTCHA);
            }
            // 验证码错误
            if($userCaptcha != $captcha){
                throw new UserException(UserException::INVALID_CAPTCHA);
            }

            $loan_users = new LoanUsers();
            $loan_users->user_phone = $phone;
            $loan_users->user_pwd = md5($password);
            $loan_users->channel_id = $channel_id;
            $loan_users->merchant_id = $merchant_id;
            $loan_users->save();

            /**
             * 对接微服务（JAVA）
             */
            $res = Helper::apiCurl(Helper::getApiUrl("register"), "POST", ['mobile' => $phone, "platUserId" => $loan_users->id]);
            $uuid = "";
            if($res['code'] == 0) {
                $uuid = $res['data']['uuid'];
            }
            $loan_users = LoanUsers::findOne($loan_users->id);
            $loan_users->uuid = $uuid;
            $loan_users->save();
        });
    }


    /**
     * 文件描述 保存浏览记录
     * Created On 2019-03-22 12:33
     * Created By heyafei
     * @param $channel_id
     * @param $unique_user
     * @param $ip
     */
    public static function channelPvLog($channel_id, $unique_user, $ip)
    {
        $model = new ChannelPvLog();
        $model->channel_id = $channel_id;
        $model->cookie = $unique_user;
        $model->ip = $ip;
        $model->date = date("Ymd");
        $model->save();
    }
}






