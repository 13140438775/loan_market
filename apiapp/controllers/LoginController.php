<?php
/**
 * RestFul example.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 2018/3/17 17:02:14
 */

namespace app\controllers;

use common\components\actions\CaptchaPictureAction;
use common\exceptions\UserException;
use app\services\LoginService;
use common\exceptions\RequestException;

class LoginController extends BaseController
{
    const MIN_PHONE_CAPTCHA = 3; // 一天内免输图形验证码次数
    const MAX_PHONE_CAPTCHA = 20; // 每个手机号每分钟只可获取一次短信验证码，每天上限20条
    const MAX_IP_CAPTCHA = 100; // 同一个IP一天内上限100条


    /**
     * 文件描述 获取app图形验证码
     * Created On 2019-02-25 18:14
     * Created By heyafei
     * @return array
     */
    public function actions()
    {
        return  [
            'captcha' => [
                'class' => CaptchaPictureAction::class,
                'fixedVerifyCode' => strval(rand(1000,9999)),
                'backColor'=>0x000000,//背景颜色
                'maxLength' => 4, //最大显示个数
                'minLength' => 4,//最少显示个数
                'padding' => \Yii::$app->request->get("padding", 5),//间距
                'height'=>\Yii::$app->request->get("height", 40),//高度
                'width' => \Yii::$app->request->get("width", 140),//宽度
                'foreColor'=>0xffffff,     //字体颜色
                'offset'=>\Yii::$app->request->get("offset", 16),//设置字符偏移量 有效果
                //'controller'=>'login',        //拥有这个动作的controller
            ]
        ];
    }

    /**
     * 文件描述 获取验证
     * Created On 2019-03-21 10:46
     * Created By heyafei
     * @return array|null
     * @throws RequestException
     * @throws UserException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public function actionCaptchaMsg()
    {
        $captcha = \Yii::$app->request->post("captcha");
        $phone = \Yii::$app->request->post("phone");
        $ip = isset($_COOKIE['REMOTE_ADDR']) ? $_COOKIE['REMOTE_ADDR']: "0.0.0.0";
        if(!isset($captcha)) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }

        $ip_captcha_times = \Yii::$app->redis->get(LoginService::getSmsCaptchaTimesIp($ip));
        // 达到IP限制次数
        if($ip_captcha_times > self::MAX_IP_CAPTCHA) {
            throw new UserException(UserException::CAPTCHA_MAX_LIMIT);
        }

        // 判断是否需要图形验证
        $captcha_times = \Yii::$app->redis->get(LoginService::getCaptchaTimesKey($phone));
        $captcha_picture = \Yii::$app->redis->get(CaptchaPictureAction::REDIS_PREFIX . "_captcha_picture_" . $phone);
        if ($captcha_times > self::MIN_PHONE_CAPTCHA) {
            // 获取短信次数达到上限20次24小时内
            if($captcha_times > self::MAX_PHONE_CAPTCHA) {
                throw new UserException(UserException::CAPTCHA_MAX_LIMIT);
            }
            // 请先完成图形验证
            if(empty($captcha_picture)) {
                throw new UserException(UserException::CHECK_CAPTCHA);
            }
            // 验证码错误
            if($captcha != $captcha_picture){
                throw new UserException(UserException::INVALID_CAPTCHA);
            }
        }

        $search = '/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/';
        if(!preg_match($search, $phone)) {
            throw new UserException(UserException::PHONE_FORMAT);
        }
        return LoginService::getCaptcha($phone, $ip);
    }

    /**
     * 文件描述 用户注册/登陆
     * Created On 2019-03-05 14:26
     * Created By heyafei
     * @return mixed
     * @throws \Throwable
     */
    public function actionRegister()
    {
        $phone = \Yii::$app->request->post('phone');
        $verifyCode = \Yii::$app->request->post('verifyCode');
        $channel_id = \Yii::$app->request->post('channel_id');
        $password = \Yii::$app->request->post('password', '123456');
        $merchant_id = \Yii::$app->request->post('merchant_id', 0);

        return LoginService::saveRegister($password, $phone, $verifyCode, $channel_id, $merchant_id);
    }

    /**
     * 文件描述 注销登录
     * Created On 2019-02-15 21:20
     * Created By heyafei
     * @throws \Exception
     */
    public function actionLogout() {
        $token = \Yii::$app->request->getHeaders()->get("authorization");
        LoginService::logout($token);
    }
}