<?php

namespace app\services;

use Yii;
use common\helpers\Helper;
use common\exceptions\SystemJobException;

class SystemJobService extends BaseService
{
    use base;

    // 获取信息类型
    const GET_INFO_TYPE = [
        'addressBookList' => 'addressBook',  // 获取通讯录信息
        'appList'         => 'appList',      // 获取app列表
        'callHistoryList' => 'callHistory',  // 获取历史通话记录
        'deviceList'      => 'deviceInfo',   // 获取设备列表
        'messageList'     => 'uploadMessage',// 获取短信列表
    ];

    /**
     * getUserInfoList 获取用户信息列表
     * @date     2019/3/18 18:08
     * @author   周晓坤<1426801685@qq.com>
     * @param $type
     * @return bool|mixed
     * @throws SystemJobException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public static function getUserInfoList($type)
    {
        $info_type = self::GET_INFO_TYPE[$type];
        if (!isset($info_type)) {
            throw new SystemJobException(SystemJobException::INVALID_TYPE);
        }
        $user = \Yii::$app->user->getIdentity();
        if (!empty($user['user_phone'])) {
            $mobile = "mobile=" . $user['user_phone'];
            return Helper::apiCurl(Helper::getApiUrl($info_type, "javaApiSecond"), 'GET', $mobile, []);
        }
        throw new SystemJobException(SystemJobException::USER_PHONE_FAIL);
    }

}