<?php
/**
 * Sign validate
 *
 * @Author     : pb@likingfit.com
 * @CreateTime 2018/7/24 11:29:19
 */

namespace open\behaviors;

use open\exceptions\RequestException;
use open\filters\RequestFilter;

class SignValidate extends RequestFilter {
    /**
     * data sign secret
     *
     * @var string
     */
    public $secretKey = [];
    
    public function beforeAction($request) {
        $signature = $request->post('sign');
        $ua = $request->post('ua');

        $secret = $this->getVersionSecret($ua);
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
     * @return string
     */
    public function getSystemSign($request, $secret) {
        $timestamp   = $request->post('timestamp');
        $uri         = rtrim($request->getPathInfo(), "/"); 
        $queryString = $request->getQueryString();          
        
        $data = [
            $secret,
            $timestamp,
            "/" . $uri . ($queryString ? "?" . $queryString : "")
        ];
        sort($data, SORT_STRING);
        return sha1(implode($data)); 
    }
    
    public function getVersionSecret($ua) {
        // todo 数据库获取secret
        return isset($this->secretKey[$ua]) ? $this->secretKey[$ua] : '';
    }
}