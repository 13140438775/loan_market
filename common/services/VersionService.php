<?php
/**
 * Created by PhpStorm.
 * @createTime: 2018/10/15 18:34
 */

namespace common\services;

class VersionService
{
    static $version;

    public function init()
    {
        self::$version = \Yii::$app->params['version'];
    }

    // 比较版本
    public static function versionCompare($version_2, $operator = "lt")
    {
        // 获取本地的版本
        $version_1 = self::$version;
        return version_compare($version_1, $version_2, $operator);
    }
}