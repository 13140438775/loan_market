<?php
/**
 * RestFul example.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 2018/3/17 17:02:14
 */

namespace app\controllers;

use app\services\H5RegisterService;
use common\components\actions\CaptchaPictureAction;
use common\exceptions\UserException;
use common\exceptions\RequestException;

class H5RegisterController extends BaseController
{
    /**
     * 文件描述 获取验证
     * Created On 2019-03-21 23:04
     * Created By heyafei
     * @return array|null
     * @throws RequestException
     * @throws UserException
     */
    public function actionH5Captcha()
    {
        $captcha = \Yii::$app->request->post("captcha");
        $phone = \Yii::$app->request->post("phone");

        // 用户已经注册
        if(H5RegisterService::checkRegister($phone)){
            throw new UserException(UserException::HAS_USER);
        }

        if(!isset($captcha)) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }

        // 判断是否需要图形验证
        $captcha_picture = \Yii::$app->redis->get(CaptchaPictureAction::REDIS_PREFIX . "_captcha_picture_" . $phone);
        // 请先完成图形验证
        if(empty($captcha_picture)) {
            throw new UserException(UserException::CHECK_CAPTCHA);
        }
        // 图形验证码错误
        if($captcha != $captcha_picture){
            throw new UserException(UserException::INVALID_CAPTCHA);
        }

        $search = '/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/';
        if(!preg_match($search, $phone)) {
            throw new UserException(UserException::PHONE_FORMAT);
        }
        return H5RegisterService::getCaptcha($phone);
    }

    /**
     * 文件描述 用户注册/登陆
     * Created On 2019-03-05 14:26
     * Created By heyafei
     * @return mixed
     * @throws \Throwable
     */
    public function actionH5Register()
    {
        $phone = \Yii::$app->request->post('phone');
        $verifyCode = \Yii::$app->request->post('verifyCode');
        $channel_id = \Yii::$app->request->post('channel_id');
        $password = \Yii::$app->request->post('password', '123456');
        $merchant_id = \Yii::$app->request->post('merchant_id', 0);

        return H5RegisterService::saveRegister($password, $phone, $verifyCode, $channel_id, $merchant_id);
    }


    public function actionChannelPvLog()
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
        return H5RegisterService::channelPvLog($channel_id, $unique_user, $ip);
    }
}