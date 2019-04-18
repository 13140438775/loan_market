<?php

namespace app\services;

/**
 * 基础类
 * Class BaseService
 * @package common\services
 */
trait Base{
    private static $instance;
    //过期时间
    private $expire = 2 * 60 * 60;

    public static function getInstance(){
        if(self::$instance==null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 生成固定key
     * @param $subKey
     *
     * @return string
     */
    private function getRedisKey($subKey){
        $controller = \Yii::$app->controller->id;
        $method = \Yii::$app->controller->action->actionMethod;

        return $controller.'_'.$method.'_'.$subKey;
    }

    /**
     * 设置redis
     * @param $key
     * @param $data
     *
     * @return mixed
     */
    private function setRedis($key,$data,$expireTime = ''){
        if(is_array($data)){
            $data = json_encode($data);
        }
        if(empty($expireTime)) $expireTime = $this->expire + rand(100,300);
        return \Yii::$app->redis->setex($key,$expireTime,$data);
    }

    /**
     * 获取redis内信息
     * @param $subKey
     *
     * @return mixed
     */
    private function getRedisInfo($subKey){
        $RedisData = \Yii::$app->redis->get($subKey);
        if(is_null($RedisData)){
            return null;
        }
        return json_decode($RedisData,true);
    }
}