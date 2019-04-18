<?php

namespace api\services;

use common\exceptions\UserException;
use common\helpers\Helper;
use common\models\LoanLoginLog;
use common\models\LoanUsers;

class LoginService
{
    public static function getSmsCaptchaKey($phone)
    {
        return REDIS_PREFIX . "_sms_captcha_" . $phone;
    }

    public static function getTokenKey($token)
    {
        return REDIS_PREFIX . "_token_" . $token;
    }

    public static function getCaptchaTimesKey($phone)
    {
        return REDIS_PREFIX . "_captcha_times_" . $phone;
    }



    /**
     * 文件描述 密码登录/短信登陆
     * Created On 2019-02-18 15:35
     * Created By heyafei
     * @param $phone
     * @param $password
     * @param $login_type
     * @return mixed
     * @throws \Throwable
     */
    public static function login($phone, $password, $login_type)
    {
        return \Yii::$app->db->transaction(function ($db) use ($phone, $password, $login_type){

            // 短信登陆/密码登陆
            if($login_type == LoanUsers::MSG_LOGIN) {
                $captcha = \Yii::$app->redis->get(self::getSmsCaptchaKey($phone));
                if(!$captcha){
                    throw new UserException(UserException::EXPIRED_CAPTCHA);
                }
                if($password != $captcha){
                    throw new UserException(UserException::INVALID_CAPTCHA);
                }
                $loan_user = LoanUsers::findOne(["user_phone" => $phone]);
                \Yii::$app->redis->del(self::getSmsCaptchaKey($phone));
            } else {
                $loan_user = LoanUsers::findOne(["user_phone" => $phone, "user_pwd" => $password]);
                if(empty($loan_user)) {
                    throw new UserException(UserException::PWD_ERROR);
                }
            }

            if($loan_user->status != LoanUsers::IS_VALID) {
                throw new UserException(UserException::INVALID_USER);
            }

            $jwt = (string)Helper::issueJwt(['id' => $loan_user->id]);
            \Yii::$app->redis->setex(self::getTokenKey($jwt), CAPTCHA_INTERVAL, $jwt);
            self::saveLoginLog($loan_user->id, $phone); // 记录登陆日志

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
     * @param $phone
     * @return null|string
     * @throws \Exception
     * @CreateTime 2018/6/28 16:54:09
     * @Author: fangxing@likingfit.com
     */
    public static function getCaptcha($phone)
    {
        $captcha = \Yii::$app->redis->get(self::getSmsCaptchaKey($phone));
        $captcha = $captcha ?:random_int(100000, 999999);

        //发送短信
        if (self::checkRegister($phone)) {
            // 登陆模版
            $msg = "【用钱金卡】尊敬的用户，本次验证码为{$captcha}，有效时间5分钟。如不是本人请忽略。提示：请勿泄露给他人";
        } else {
            // 注册模版
            $msg = "【用钱金卡】尊敬的用户，本次验证码为{$captcha}，有效时间5分钟。如不是本人请忽略。提示：请勿泄露给他人";
        }
        \Yii::$app->sms->send($phone, $msg);

        // 存入redis
        \Yii::$app->redis->setex(self::getSmsCaptchaKey($phone), CAPTCHA_INTERVAL, $captcha);
        \Yii::$app->redis->sadd(self::getCaptchaTimesKey($phone), $captcha);
        \Yii::$app->redis->expireat(self::getCaptchaTimesKey($phone), strtotime("+1 days", strtotime(date("Ymd"))));
        if (YII_ENV_DEV | YII_ENV_TEST){
            return (string)$captcha;
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
        // TODO token过期时间置为无效
        \Yii::$app->redis->setex(self::getTokenKey($token), TOKEN_OVERDUE, $token);
        return;
    }

    /**
     * 文件描述 登陆日志
     * Created On 2019-02-18 10:31
     * Created By heyafei
     * @param $loan_user_id
     * @param $phone
     */
    public static function saveLoginLog($loan_user_id, $phone)
    {
        $loan_login_log = new LoanLoginLog();
        $loan_login_log->user_id = $loan_user_id;
        $loan_login_log->client_type = \Yii::$app->request->getHeaders('deviceType');
        $loan_login_log->user_phone = $phone;
        $loan_login_log->ip_address = \Yii::$app->request->getHeaders('deviceType');
        $loan_login_log->device_id = \Yii::$app->request->getHeaders('deviceId');
        $loan_login_log->app_version = \Yii::$app->request->getHeaders('appVersion');
        $loan_login_log->os_version = \Yii::$app->request->getHeaders('osVersion');
        $loan_login_log->save();
    }

    /**
     * 文件描述 注册
     * @param $password
     * @param $phone
     * @param $userCaptcha
     * @param $channel_id
     * @param $merchant_id
     * @return mixed
     * @throws UserException
     * @throws \Throwable
     */
    public static function saveRegister($password, $phone, $userCaptcha, $channel_id, $merchant_id)
    {
        if(self::checkRegister($phone)){
            throw new UserException(UserException::HAS_USER);
        }

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

            return;
        });
    }

    /**
     * 文件描述 忘记密码
     * @param $password
     * @param $phone
     * @param $userCaptcha
     * @return mixed
     * @throws \Throwable
     */
    public static function saveResetLoginPassword($password, $phone, $userCaptcha)
    {
        return \Yii::$app->db->transaction(function ($db) use ($password, $phone, $userCaptcha){
            $captcha = \Yii::$app->redis->get(self::getSmsCaptchaKey($phone));
            if(!$captcha){
                throw new UserException(UserException::EXPIRED_CAPTCHA);
            }
            if($userCaptcha != $captcha){
                throw new UserException(UserException::INVALID_CAPTCHA);
            }

            $loan_users = LoanUsers::find()->where(['user_phone' => $phone])->one();
            if (!$loan_users || $loan_users->status !== 0){
                throw new UserException(UserException::INVALID_USER);
            }
            $loan_users->user_pwd = md5($password);
            $loan_users->save();

            return;
        });
    }

    /**
     * 文件描述 更新密码
     * Created On 2019-02-19 15:40
     * Created By heyafei
     * @param $password
     * @param $oldpassword
     * @param $userId
     * @return mixed
     * @throws \Throwable
     */
    public static function saveUpdateLoginPassword($password, $oldpassword, $userId)
    {
        $oldpassword = md5($oldpassword);
        return \Yii::$app->db->transaction(function ($db) use ($password, $oldpassword, $userId){
            $loan_users = LoanUsers::find()->where(['and', "id={$userId}", "user_pwd='{$oldpassword}'"])->one();
            if(!$loan_users){
                throw new UserException(UserException::OLD_PWD_ERROR);
            }
            $loan_users->user_pwd = md5($password);
            $loan_users->save();

            return;
        });
    }
}






