<?php
/**
 * Helper func
 *
 * @Author     : pb@likingfit.com
 * @CreateTime 27/07/2018 16:56:17
 */

namespace common\helpers;

use common\exceptions\BaseException;
use common\models\LoanUsers;
use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use sizeg\jwt\Jwt;

class Helper
{
    const API_SUCCESS = 0;
    const OPEN_SUCCESS = 1;

    /**
     * @param       $url
     * @param       $method
     * @param array $data
     * @param array $options
     *
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @CreateTime 2018/6/1 11:14:04
     * @Author: fangxing@likingfit.com
     */
    public static function curlNew($url, $method, $data = [], $options = [])
    {
        $default = [
            'base_uri' => '',
            'connect_timeout' => 10,
            'timeout' => 10,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
        $options = array_merge($default, $options);
        $client = new Client($options);
        try {
            if (strtoupper($method) === 'GET' && $data) {
                $response = $client->request($method, $url, ['query' => $data]);
            } else {
                $response = $client->request($method, $url, $data);
            }

            if ($response->getStatusCode() != 200) {
                \Yii::error("API:{$url}请求,httpCode为：".$response->getStatusCode());
                return false;
            }
            $content = $response->getBody()->getContents();
            \Yii::info("API:{$url}请求,返回数据：".$content);
            return json_decode($content, true);
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * generate random string by specify length
     *
     * @param $length
     *
     * @return string
     * @throws \yii\base\Exception
     * @CreateTime 2018/5/31 11:59:07
     * @Author     : fangxing@likingfit.com
     */
    public static function generateRandomStr($length)
    {
        $security = Yii::$app->getSecurity();

        return $security->generateRandomString($length);
    }

    /**
     * @param array $privatePayloads
     *
     * @return \Lcobucci\JWT\Token
     * @throws \yii\base\Exception
     * @CreateTime 2018/9/14 15:50:03
     * @Author: heyafei@likingfit.com
     */
    public static function issueJwt($privatePayloads = [])
    {
        /**
         * @var Jwt $jwt
         */
        $jwt = \Yii::$app->jwt;
        $request = \Yii::$app->request;
        $sign = new Sha256();
        $time = time();
        $uid = self::randomStr();
        $builder = $jwt->getBuilder()
            ->setIssuer($request->getHostName())
            ->setIssuedAt($time)
            ->setAudience($request->getRemoteHost())
            ->setId($uid)
            ->setNotBefore($time - 10)
            ->setExpiration($time + 86400 * 30);
        foreach ($privatePayloads as $name => $v) {
            $builder->set($name, $v);
        }
        return $builder->sign($sign, $jwt->key)->getToken();
    }

    /**
     * @param string $prefix
     * @param int    $length
     * @param string $suffix
     *
     * @return string
     * @throws \yii\base\Exception
     * @CreateTime 2018/9/14 15:45:31
     * @Author: heyafei@likingfit.com
     */
    public static function randomStr($prefix = '', $length = 32, $suffix = '')
    {
        $security = \Yii::$app->getSecurity();
        return $prefix . $security->generateRandomString($length) . $suffix;
    }


    /**
     * 使用指定下标对数据进行重建索引
     *
     * @param $array
     * @param $key
     *
     * @return array
     */
    public static function mapByKey($array, $key)
    {
        $map = [];
        foreach ($array as $item) {
            if (!isset($item[$key]) || (!is_string($item[$key]) && !is_numeric($item[$key]))) {
                continue;
            }
            $map[$item[$key]] = $item;
        }

        return $map;
    }

    /**
     * 使用指定下标对数据进行重建分组索引
     * @param $array
     * @param $key
     *
     * @return array
     */
    public static function groupByKey($array, $key)
    {
        $map = [];
        foreach ($array as $item) {
            if (!isset($item[$key]) || (!is_string($item[$key]) && !is_numeric($item[$key]))) {
                continue;
            }
            $map[$item[$key]][] = $item;
        }

        return $map;
    }

    /**
     * 生成API调用链接
     * Created On 2019-03-11 14:44
     *
     * @param        $method
     * @param string $apiPrefix
     *
     * @return string
     */
    public static function getApiUrl($method, $apiPrefix = 'javaApi')
    {
        return \Yii::$app->params[$apiPrefix] . \Yii::$app->params['appList'][$method];
    }

    /**
     * 驼峰转下划线
     *
     * @param        $camelCaps
     * @param string $separator
     *
     * @return array
     */
    public static function uncamelize($camelCaps, $separator = '_')
    {
        $data = array();
        if (empty($camelCaps)) {
            return [];
        }
        foreach ($camelCaps as $key => $val) {
            $keyValue = strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $key));
            $data[$keyValue] = $val;
        }
        return $data;
    }

    /**
     * 统一包装返回JAVA接口及数据处理
     *
     * @param       $url
     * @param       $method
     * @param array $data
     * @param array $options
     * @param String  $type
     * @param bool  $isUncamelize
     *
     * @return bool|mixed
     * @throws BaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public static function apiCurl($url, $method, $data = [], $options = [], $type = 'form_params',$isUncamelize = false)
    {
        $headers = [
            'headers' => [
                "Authorization" => "Basic ".base64_encode("plat_yqjk:123456"),
            ],
        ];
        $options = array_merge($options,$headers);

        // 登陆者信息传送
        if( isset(\Yii::$app->user) && \Yii::$app->user && \Yii::$app->user->getIdentity() ) {
            $user = \Yii::$app->user->getIdentity();
            $params = [
                "ownerPhone" => isset($user->user_phone) ? $user->user_phone: "",
                "platUserId" => isset($user->id) ? $user->id: ""

            ];
            $data = array_merge($data, $params);
        }

        try {
            if(strtoupper($method) === 'POST') $data[$type] = $data;
            $response = self::curlNew($url, $method, $data,$options);
            if ($response === false || $response['code'] != self::API_SUCCESS) {
                $jsonStr = json_encode($data);
                \Yii::error("API:{$url}请求失败,请求数据：{$jsonStr}, error: {$response['msg']}");
                throw new BaseException(BaseException::BASIC_ERROR, $response['msg']);
            }
        } catch (GuzzleException $e) {
            throw new BaseException(BaseException::SYSTEM_ERR);
        } catch (ClientException $e) {
            throw new BaseException(BaseException::SYSTEM_ERR);
        }
        if ($isUncamelize) {
            $response['data'] = self::uncamelize(json_decode($response['data'], true));
        }
        return $response;
    }


    /**
     * 文件描述 open对接URL
     * Created On 2019-03-11 15:35
     * Created By heyafei
     * @param $open_url
     * @param $method
     * @return string
     */
    public static function getOpenApi($open_url, $method)
    {
        return $open_url;
    }

    /**
     * 文件描述 生成签名
     * Created On 2019-03-11 15:41
     * Created By heyafei
     * @param $ua
     * @param $key
     * @param $call
     * @param $args
     * @return string
     */
    public static function setSignKey($ua, $key, $call, $args)
    {
        $signKey = "{$ua}{$key}{$ua}";
        $sign = md5("{$signKey}{$call}{$signKey}{$args}{$signKey}");
        return $sign;
    }

    /**
     * 文件描述 对接机构API请求
     * Created On 2019-03-11 15:11
     * Created By heyafei
     * @param $url
     * @param $method
     * @param array $data
     * @param array $options
     * @return bool|mixed
     * @throws BaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function openCurl($url, $method, $data = [], $options = [])
    {
        try {
            if(strtoupper($method) == 'POST'){
                $data = ['multipart'=>$data];
            }

            \Yii::info("API:{$url}请求,请求数据：".json_encode($data));
            $response = self::curlNew($url, $method, $data, $options);
            \Yii::info("API:{$url}请求,接受数据：".json_encode($response));
            if ($response && $response['status'] != self::OPEN_SUCCESS ) {
                $jsonStr = json_encode($data);
                \Yii::error("API:{$url}请求失败,请求数据：{$jsonStr}, error: {$response['message']}");
                $code = BaseException::BASIC_ERROR;

                if($response['status'] == 500){
                    $code = BaseException::SYSTEM_ERR;
                }
                throw new BaseException($code, $response['message']);
            } elseif ($response === false) {
                throw new BaseException(BaseException::SYSTEM_ERR);
            }
        } catch (GuzzleException $e) {
            throw new BaseException(BaseException::SYSTEM_ERR);
        } catch (ClientException $e) {
            throw new BaseException(BaseException::SYSTEM_ERR);
        }
        return $response;
    }

    /**
     * 获取年龄，计算月份
     * @param $birthday
     *
     * @return false|string
     */
    public static function getAge($birthday,$type = ''){
        $birthdayTime = strtotime($birthday);
        $byear = date('Y',$birthdayTime);
        $bmonth = date('m',$birthdayTime);
        $bday = date('d',$birthdayTime);

        //格式化当前时间年月日
        $tyear = date('Y');
        $tmonth = date('m');
        $tday = date('d');

        if(empty($type)) {
            $age = $tyear - $byear;
            if ($bmonth > $tmonth || $bmonth == $tmonth && $bday > $tday) {
                $age--;
            }
            return $age;
        }else{
            return abs($tyear - $byear) * 12 - $bmonth[1] + abs($tmonth[1]);
        }
    }
}