<?php

namespace app\services;


use common\models\HelpCenter;
use common\services\HelpService;

class UserCenterService
{
    /**
     * 文件描述 帮助中心
     */
    public static function helpCenter()
    {
        $res =[];
        $list = HelpCenter::find()->select(['tip', 'content', 'content_type'])->asArray()->all();
        $help_center_list = HelpService::array_group_by($list, "content_type");
        foreach ($help_center_list AS $key => $val) {
            $res[] = [
                "title" => HelpCenter::$content_type_set[$key],
                "content" => $val
            ];
        }
        return $res;
    }
}

