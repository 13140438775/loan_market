<?php
/**
 * Sign validate
 *
 * @Author     : pb@likingfit.com
 * @CreateTime 2018/7/24 11:29:19
 */

namespace common\behaviors;

use common\exceptions\RequestException;
use common\filters\RequestFilter;

class SignValidate extends RequestFilter {
    /**
     * data sign secret
     *
     * @var string
     */
    public $secretKey = [];
    
    public function beforeAction($request) {
        $header    = $request->getHeaders();
        $version   = $header->get('app-version');
        $signature = $header->get('signature');
        
        $secret = $this->getVersionSecret($version);
        $signString = $this->getSystemSign($request, $secret);
        if ($signature != $signString) {
            throw new RequestException(RequestException::INVALID_SIGNATURE);
        }
    }
    /**
     * 获取签名
     * @Date: 2019-01-24 10:22:18
     * @param object $request  请求对象
     * @param string $secret   请求秘钥,目前$secret = !@#$%%^&*(^^)
     * @return: string
     */
    public function getSystemSign($request, $secret) {
        $header      = $request->getHeaders();              
        $deviceId   = $header->get('device-id');
        $timestamp   = $header->get('request-time');
        $channel_id   = $header->get('channel-id');
        $uri         = rtrim($request->getPathInfo(), "/");
        $queryString = $request->getQueryString();
        
        $data = [
            $secret,
            $timestamp,
            $deviceId,
//            "/" . $uri . ($queryString ? "?" . $queryString : "")
            $channel_id
        ];
        sort($data, SORT_STRING);
        return sha1(implode($data));
    }
    
    public function getVersionSecret($version) {
        return isset($this->secretKey[$version]) ? $this->secretKey[$version] : '';
    }
}