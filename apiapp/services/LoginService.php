<?php

namespace app\services;

use common\exceptions\UserException;
use common\helpers\Helper;
use common\models\LoanLoginLog;
use common\models\LoanUsers;

class LoginService
{
    const REDIS_PREFIX = "loan_market";
    const CAPTCHA_INTERVAL = 60 * 5;
    const TOKEN_EXPIRE = 30 * 60 * 60 * 24;
    const LOGIN_TEMPLATE = 101;
    const REGISTER_TEMPLATE = 104;

    public static function getSmsCaptchaKey($phone)
    {
        return self::REDIS_PREFIX . "_app_sms_captcha_" . $phone;
    }

    public static function getTokenKey($user_id)
    {
        return self::REDIS_PREFIX . "_app_token_" . $user_id;
    }

    public static function getCaptchaTimesKey($phone)
    {
        return self::REDIS_PREFIX . "_app_captcha_times_" . $phone;
    }

    public static function getSmsCaptchaTimesIp($ip)
    {
        return self::REDIS_PREFIX . "_app_sms_captcha_times_ip" . $ip;
    }



    /**
     * 文件描述 密码登录/短信登陆
     * Created On 2019-02-18 15:35
     * Created By heyafei
     * @param $phone
     * @param $captcha
     * @return mixed
     * @throws \Throwable
     */
    public static function login($phone, $captcha)
    {
        return \Yii::$app->db->transaction(function ($db) use ($phone, $captcha){

            // 短信登陆
            $sms_captcha = \Yii::$app->redis->get(self::getSmsCaptchaKey($phone));
            if(!$sms_captcha){
                throw new UserException(UserException::EXPIRED_CAPTCHA);
            }
            if($captcha != $sms_captcha){
                throw new UserException(UserException::INVALID_CAPTCHA);
            }
            $loan_user = LoanUsers::findOne(["user_phone" => $phone]);
            \Yii::$app->redis->del(self::getSmsCaptchaKey($phone));

            if($loan_user->status != LoanUsers::IS_VALID) {
                throw new UserException(UserException::INVALID_USER);
            }

            $jwt = (string)Helper::issueJwt(['id' => $loan_user->id]);
            \Yii::$app->redis->setex(self::getTokenKey($loan_user->id), self::TOKEN_EXPIRE, $jwt);

            /**
             * 对接微服务（JAVA）
             */
            $res = Helper::apiCurl(Helper::getApiUrl("register"), "POST", ['mobile' => $phone, "platUserId" => $loan_user->id]);
            if($res['code'] == 0) {
                $loan_user->uuid = $res['data']['uuid'];
                $loan_user->save();
            }
            self::saveLoginLog($loan_user->id, $phone, $loan_user->uuid); // 记录登陆日志

            return [
                "user_id" => $loan_user->id,
                "token" => $jwt,
            ];
        });
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
     * Created On 2019-03-13 14:46
     * Created By heyafei
     * @param $phone
     * @param $ip
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public static function getCaptcha($phone, $ip)
    {
        $captcha = random_int(100000, 999999);

        // 发送短信
        $biz_type = self::checkRegister($phone) ? self::LOGIN_TEMPLATE: self::REGISTER_TEMPLATE;
//        CommonMethodsService::javaSmsApiCurl($biz_type, $captcha, $phone);

        // 存入redis
        \Yii::$app->redis->setex(self::getSmsCaptchaKey($phone), self::CAPTCHA_INTERVAL, $captcha);
        \Yii::$app->redis->incr(self::getCaptchaTimesKey($phone));
        \Yii::$app->redis->expireat(self::getCaptchaTimesKey($phone), strtotime("+1 days", strtotime(date("Ymd"))));
        // 同一IP短信数达到上限
        \Yii::$app->redis->incr(self::getSmsCaptchaTimesIp($ip));
        \Yii::$app->redis->expireat(self::getSmsCaptchaTimesIp($ip), strtotime("+1 days", strtotime(date("Ymd"))));

        if (YII_ENV_DEV | YII_ENV_TEST){
            return ['captcha' => (string)$captcha];
        }
        return null;
    }

    /**
     * 注销登录
     * @param $token
     * @throws \Exception
     * @CreateTime 2018/10/24 11:03:22
     * @Author: heyafei@likingfit.com
     */
    public static function logout($token) {
        $user_id = \Yii::$app->jwt->loadToken($token)->getClaim('id');
        \Yii::$app->redis->del(self::getTokenKey($user_id));
        return;
    }

    /**
     * 文件描述 登陆日志
     * Created On 2019-03-11 16:38
     * Created By heyafei
     * @param $loan_user_id
     * @param $phone
     * @param $uuid
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public static function saveLoginLog($loan_user_id, $phone, $uuid)
    {
        $loan_login_log = new LoanLoginLog();
        $loan_login_log->user_id = $loan_user_id;
        $loan_login_log->client_type = \Yii::$app->request->getHeaders()->get('device-type');
        $loan_login_log->user_phone = $phone;
        $loan_login_log->ip_address = $_SERVER['REMOTE_ADDR'];
        $loan_login_log->device_id = \Yii::$app->request->getHeaders()->get('device-id');
        $loan_login_log->app_version = \Yii::$app->request->getHeaders()->get('app-version');
        $loan_login_log->os_version = \Yii::$app->request->getHeaders()->get('os-version');
        $loan_login_log->update_time = date("Y-m-d H:i:s");
        $loan_login_log->create_time = date("Y-m-d H:i:s");
        $loan_login_log->save();

        $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"]: "";
        $data = [
            'appVersion' => \Yii::$app->request->getHeaders()->get("app-version"),
            'equipmentNumber' => \Yii::$app->request->getHeaders()->get("device-id"),
            'loginIp' => $ip,
            'password' => "",
            'platform' => \Yii::$app->request->getHeaders()->get("device-type"),
            'systemVersion' => \Yii::$app->request->getHeaders()->get("os-version"),
            'token' => "",
            "ownerPhone" => $phone,
            "platUserId" => $loan_user_id
        ];
        Helper::apiCurl(Helper::getApiUrl("login", "javaApiSecond"), "POST", $data, [], "json");
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
            return self::login($phone, $userCaptcha);
        } else {
            return \Yii::$app->db->transaction(function ($db) use ($password, $phone, $userCaptcha, $channel_id, $merchant_id){
                $captcha = \Yii::$app->redis->get(self::getSmsCaptchaKey($phone));
                if(!$captcha){
                    throw new UserException(UserException::EXPIRED_CAPTCHA);
                }
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

                $jwt = (string)Helper::issueJwt(['id' => $loan_users->id]);
                \Yii::$app->redis->setex(self::getTokenKey($loan_users->id), self::TOKEN_EXPIRE, $jwt);
                self::saveLoginLog($loan_users->id, $phone, $uuid); // 记录登陆日志

                return [
                    "user_id" => $loan_users->id,
                    "token" => $jwt,
                ];
            });
        }

    }
}






