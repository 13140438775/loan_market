<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/23
 * Time: 下午4:23
 */

namespace app\services;

use common\helpers\Helper;
use common\models\Area;

class AreaService
{
    use Base;
    CONST YEAR = 86400 * 365;
    /**
     * 获取区域数据
     * @param $parentid
     * @return
     */
    public function areaList($parentid = '001'){
        $cachekey = $this->getRedisKey("parent_:".$parentid);
        $cityGroup = $this->getRedisInfo($cachekey);
        if (is_null($cityGroup)){
            $citydata = Area::find()->select(['parent','keyname','name'])
                ->where(['status'=>1])->asArray()->all();
            $cityGroup = Helper::groupByKey($citydata,'parent');
            $this->setRedis($cachekey,$cityGroup,self::YEAR);
        }
        return $cityGroup;
    }
}