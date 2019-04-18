<?php

namespace backend\services;

use common\models\LoanLoginLog;
use common\models\LoanUsers;

class GetChannelDataServices
{
    /**
     * 文件描述 获取注册数
     * Created On 2019-02-21 14:47
     * Created By heyafei
     * @param $date
     * @param $channel_id
     * @return int|string
     */
    public static function statsRegisterData($date, $channel_id)
    {
        $date_begin = date("Y-m-d H:i:s", strtotime($date));
        $date_end = date("Y-m-d H:i:s", strtotime($date) + 24 * 60 * 60);

        // 1.统计注册数据
        $register_count = LoanUsers::find()
            ->where([">=", 'create_time', $date_begin])
            ->andWhere(["<=", 'create_time', $date_end])
            ->andWhere(["channel_id" => $channel_id])
            ->count();
        return $register_count;
    }

    /**
     * 文件描述 获取登陆数
     * Created On 2019-02-21 14:48
     * Created By heyafei
     * @param $date
     * @param $channel_id
     * @return int|string
     */
    public static function statsLoginData($date, $channel_id)
    {
        $date_begin = date("Y-m-d H:i:s", strtotime($date));
        $date_end = date("Y-m-d H:i:s", strtotime($date) + 24 * 60 * 60);

        // 2.统计登陆数
        $login_data = LoanLoginLog::find()
            ->innerJoin("loan_users", "loan_users.id = loan_login_log.user_id")
            ->where([">=", 'loan_login_log.create_time', $date_begin])
            ->andWhere(["<=", 'loan_login_log.create_time', $date_end])
            ->andWhere(["loan_users.channel_id" => $channel_id])
            ->count();
        return $login_data;
    }
}
