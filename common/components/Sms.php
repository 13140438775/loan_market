<?php
namespace common\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use yii\base\Component;

class Sms extends Component
{
    public $url = "http://127.0.0.1:4151/pub?topic=COMMA_SMS";

    public $captcha_tmp = 108628;

    /**
     * 发送验证码
     * Created On 2019-02-19 14:14
     * Created By heyafei
     * @param $phone
     * @param $captcha
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendCaptcha($phone, $captcha)
    {
        if(YII_ENV_TEST || YII_ENV_PROD){
            return $this->sendSms($phone, $this->captcha_tmp, $captcha);
        }
        return true;
    }

    /**
     * 发送短信
     * Created On 2019-02-19 14:14
     * Created By heyafei
     * * @param $phones
     * @param $templateId
     * @param null $smsData
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSms ($phones, $templateId, $smsData = null) {

        if (!is_array($phones)) {
            $phones = [$phones];
        }

        $params = [
            'phones'      => $phones,
            'template_id' => $templateId,
            'data'        => $smsData,
        ];

        $client = new Client(
            [
                'timeout' => 2.0,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        try {
            $client->request('POST', $this->url, ['json' => $params]);
            return true;
        } catch (ClientException $e) {
            \Yii::info("短信模版ID：{$templateId}发送失败, error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * 文件描述
     * Created On 2019-02-27 20:03
     * Created By heyafei
     * @param $mobile
     * @param $msgContent
     * @return bool
     */
    public function send($mobile,$msgContent)
    {
        //$msgContent = iconv("UTF-8", "gbk//IGNORE", $msgContent);//utf8 转gbk
        $url = 'http://120.76.79.226:9080/Message.sv?method=sendMsg';
        $postdata = [
            'userCode' => 'yqjkyy',//帐号
            'userPwd' => 'yqjk890',//密码
            'numbers' => $mobile,//号码
            'msgContent' => $msgContent,
            'charset' => 'UTF-8', //请按平台实际编码填写，UTF-8或gbk
        ];

        $ch = curl_init();
        $postdata = http_build_query($postdata,'&');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $resultdata = curl_exec($ch);
        $status = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($errno){//出错则显示错误信息
            \Yii::info("短信发送失败, error: {$error}");
            return false;
        }
        return true;
    }
}