<?php
/**
 * Created by PhpStorm.
 * @author: gaoqiang@likingfit.com
 * @createTime: 2018/10/15 18:34
 */

namespace common\services;


use common\models\LoanLoginLog;
use common\models\Orders;

class CommonModelService
{
    // 放款用户
    public static function LoanSuccess($user_id)
    {
        $loan_status = Orders::LOAN_SUCCESS_LIST;
        $where = [
            'and',
            ['user_id' => $user_id],
            ['in', 'status', $loan_status]
        ];
        $res = Orders::find()->where($where)->one();
        if($res) return true;
        return false;
    }


    // 用户最早的登陆时间
    public static function UserLoginTime($user_id)
    {
        $login_log = LoanLoginLog::find()->filterWhere(['user_id' => $user_id])->orderBy("create_time ASC")->asArray()->one();
        if(!$login_log) {
            return 0;
        } else {
            return $login_log['create_time'];
        }
    }

    // 还款用户
    public static function RepayUser($user_id)
    {
        $loan_status = Orders::NO_REPAY;
        $where = [
            'and',
            ['user_id' => $user_id],
            ['in', 'status', $loan_status]
        ];
        $res = Orders::find()->where($where)->one();
        if($res) return true;
        return false;
    }
}