<?php
/**
 * RestFul example.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 2018/3/17 17:02:14
 */

namespace api\controllers;

use common\components\actions\CaptchaPictureAction;
use common\exceptions\UserException;
use api\services\LoginService;
use common\exceptions\RequestException;

class LoginController extends BaseController
{
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
                'padding' => 5,//间距
                'height'=>40,//高度
                'width' => 140,  //宽度
                'foreColor'=>0xffffff,     //字体颜色
                'offset'=>16,        //设置字符偏移量 有效果
                //'controller'=>'login',        //拥有这个动作的controller
            ]
        ];
    }

    /**
     * 获取验证码
     * @return array
     * @throws UserException
     * @throws \Exception
     * @CreateTime 2018/9/14 15:15:43
     * @Author: heyafei@likingfit.com
     */
    public function actionCaptchaMsg()
    {
        $kaptcha = \Yii::$app->request->post("kaptcha");
        if(!isset($kaptcha)) {
            throw new RequestException(RequestException::INVALID_PARAM);
        }
        $phone = \Yii::$app->request->post("phone");

        if(LoginService::checkRegister($phone)) {
            throw new UserException(UserException::HAS_USER);
        }

        // 判断是否需要图形验证
        $res = \Yii::$app->redis->scard(LoginService::getCaptchaTimesKey($phone));
        $captcha_picture = \Yii::$app->redis->get(REDIS_PREFIX . "_captcha_picture_" . $phone);
        if ($res) {

            if(empty($captcha_picture)) {
                throw new UserException(UserException::CHECK_CAPTCHA);
            }

            if($kaptcha != $captcha_picture){
                throw new UserException(UserException::INVALID_CAPTCHA);
            }
        }

        $search = '/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/';
        if(!preg_match($search, $phone)) {
            throw new UserException(UserException::PHONE_FORMAT);
        }
        $captcha = LoginService::getCaptcha($phone);
        return ['captcha' => $captcha];
    }

    /**
     * 文件描述 密码登录/短信登陆
     * Created On 2019-02-15 21:20
     * Created By heyafei
     * @return mixed
     * @throws \Throwable
     */
    public function actionLogin()
    {
        $login_type = \Yii::$app->request->post("loginType");
        $password = \Yii::$app->request->post("password");
        $phone = \Yii::$app->request->post("phone");
        $info = LoginService::login($phone, $password, $login_type);
        return $info;
    }

    /**
     * 文件描述 注销登录
     * Created On 2019-02-15 21:20
     * Created By heyafei
     * @throws \Exception
     */
    public function actionLogout() {
        $token = \Yii::$app->request->getHeaders()->get("Token");
        LoginService::logout($token);
    }


    /**
     * 文件描述 用户注册
     * Created On 2019-02-18 14:09
     * Created By lianbingchao
     * @throws UserException
     * @throws \Throwable
     */
    public function actionRegister()
    {
        $password = \Yii::$app->request->post('password', '123456');
        $phone = \Yii::$app->request->post('phone');
        $verifyCode = \Yii::$app->request->post('verifyCode');
        $channel_id = \Yii::$app->request->post('channel_id');
        $merchant_id = \Yii::$app->request->post('merchant_id') ?? 0;

        LoginService::saveRegister($password, $phone, $verifyCode, $channel_id, $merchant_id);
    }

    /**
     * 文件描述 忘记密码
     * Created On 2019-02-18 14:52
     * Created By lianbingchao
     * @throws \Throwable
     */
    public function actionResetLoginPassword()
    {
        $password = \Yii::$app->request->post('password');
        $phone = \Yii::$app->request->post('phone');
        $smscode = \Yii::$app->request->post('smscode');

        LoginService::saveResetLoginPassword($password, $phone, $smscode);
    }

    /**
     * 文件描述 更新密码
     * Created On 2019-02-18 15:21
     * Created By lianbingchao
     * @throws \Throwable
     */
    public function actionUpdateLoginPassword()
    {
        $password = \Yii::$app->request->post('password');
        $oldpassword = \Yii::$app->request->post('oldpassword');
        $userId = \Yii::$app->request->post('userId');

        LoginService::saveUpdateLoginPassword($password, $oldpassword, $userId);
    }
}