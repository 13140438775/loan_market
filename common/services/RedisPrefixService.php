<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019-03-16
 * Time: 09:56
 */

namespace common\services;

//需要后台操作时同步删除的redis
class RedisPrefixService
{
    //活动详情 需要 产品ID
    CONST PRODUCT_DETAIL = 'product_actionView_';

    //前端分组，无其他参数
    CONST FRONT_GROUP = 'user-info_actionLoad_frontGroup';

    //const

    /**
     * 根据key名删除缓存
     * @param $key
     *
     * @return mixed
     */
    public function delRedisByKey($key){
        return \Yii::$app->redis->del($key);
    }
}