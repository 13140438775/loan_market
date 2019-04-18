<?php

namespace api\services;

use yii\base\Object;

/**
 * 基础类
 * Class BaseService
 * @package common\services
 */
class BaseService extends Object {

    const NO_VALID = 0;
    const IS_VALID = 1;


    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * 文件描述 主要是事务使用
     * Created On 2019-01-21 10:21
     * Created By heyafei
     * @param array $result
     * @return bool
     */
    public static function checkFail($result = [])
    {
        if(empty($result)) {
            return true;
        }
        if(in_array(0, $result) || in_array(false, $result)) return true;
        return false;
    }

}