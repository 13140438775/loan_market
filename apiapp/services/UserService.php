<?php

namespace app\services;

use common\helpers\Helper;
use common\models\LoanUsers;
use common\exceptions\BaseException;

/**
 * 对接基础平台方法
 * Class UserService
 * @package app\services
 */
class UserService
{
    use Base;
    /**
     * 身份证号码检验
     * @param $cardNo
     * @return bool
     */
   public function validateIdcard($cardNo){
       if (strlen($cardNo) != 18) {
           return false;
       }
       $sum = 0;
       //身份证各位检验系数
       $coe = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
       //身份证未位校验码
       $map = array(1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2);
       for ($i = 17; $i > 0; $i--) {
           $sum += $cardNo[17 - $i] * (pow(2, $i) % 11);
       }
       $tail = substr($cardNo, 17, 1);
       //var_dump($map[$sum%11], $tail);exit;
       return ($map[$sum % 11] == $tail);
   }

    /**
     * 姓名，身份证号码二要素 认证
     * @param $name
     * @param $cardNo
     *
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public function CheckUserBasic($name,$cardNo){
        $data = ['name' => $name, 'cardNo' => $cardNo];

        try {
            Helper::apiCurl(Helper::getApiUrl('authIdCard'),'POST',$data);

            return LoanUsers::updateAll(['real_name'=>$name,'card_id'=>$cardNo], ['id'=>\Yii::$app->user->getId()]);
        } catch (BaseException $e) {
            \Yii::error("用户：{$cardNo}实名认证失败, error: {$e->getMessage()}");
            throw new BaseException(BaseException::SYSTEM_ERR);
        }
    }

}

