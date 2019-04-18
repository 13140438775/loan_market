<?php
/**
 * RestFul example.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 2018/3/17 17:02:14
 */

namespace common\components\actions;
use yii\captcha\CaptchaAction;
use yii\web\Response;
use yii\helpers\Url;

class CaptchaPictureAction extends CaptchaAction
{
    const REDIS_PREFIX = "loan_market";
    const CAPTCHA_PICTURE_INTERVAL = 60;

    public function run()
    {
        if (\Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) !== null) {
            $code = $this->getVerifyCode(true);
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'hash1' => $this->generateValidationHash($code),
                'hash2' => $this->generateValidationHash(strtolower($code)),
                'url' => Url::to([$this->id, 'v' => uniqid('', true)]),
            ];
        }

        // 验证码存redis
        $RCaptchaKey = \Yii::$app->request->getQueryParam("RCaptchaKey");
        \Yii::$app->redis->executeCommand("setex", [self::REDIS_PREFIX . "_captcha_picture_" . $RCaptchaKey, self::CAPTCHA_PICTURE_INTERVAL, $this->fixedVerifyCode]);

        $this->setHttpHeaders();
        \Yii::$app->response->format = Response::FORMAT_RAW;
        return $this->renderImage($this->getVerifyCode());
    }

}