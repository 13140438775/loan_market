<?php
/**
 * Jwt.
 *
 * @Author     : heyafei
 * Created On 2019-01-22 11:24
 */

namespace common\components;

use Yii;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class Jwt extends \sizeg\jwt\Jwt {
    public $key;
    
    public $expTime = 3600 * 2;
    
    public function issue($privatePayloads = []) {
        $request = Yii::$app->request;
        $security = Yii::$app->getSecurity();
        $sign = new Sha256();
        $time = time();
        $uid = $security->generateRandomString();
        $builder = $this->getBuilder()
            ->setIssuer($request->getHostName())
            ->setIssuedAt($time)
            ->setAudience($request->getRemoteHost())
            ->setId($uid)
            ->setNotBefore($time - 10)
            ->setExpiration($time + $this->expTime);
        foreach ($privatePayloads as $name => $v) {
            $builder->set($name, $v);
        }
        
        return $builder->sign($sign, $this->key)->getToken();
    }
}