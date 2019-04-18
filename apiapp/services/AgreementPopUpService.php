<?php

namespace app\services;

use common\models\Apps;
use common\models\PackageApp;
use common\models\AgreementPopUp;
use common\exceptions\BaseException;
use common\exceptions\AgreementPopUpException;

/**
 * 协议弹出框服务
 * Class AgreementPopUpService
 */
class AgreementPopUpService extends BaseService
{
    /**
     * getAgreementPopUp 查询用户协议弹出框
     * @date     2019/3/13 16:36
     * @author   周晓坤<1426801685@qq.com>
     * @param $userId
     * @param $packageCode
     *
     * @return AgreementPopUp|null
     */
    public static function getAgreementPopUp($userId, $packageCode)
    {
        try {
            $app = Apps::find()
                ->alias('a')
                ->innerJoin(PackageApp::tableName() . ' AS p', "p.app_code = a.app_code")
                ->where(['p.package_code' => $packageCode])
                ->asArray()
                ->one();
            if (!empty($app['id'])) {
                return AgreementPopUp::findOne(['user_id' => $userId, 'app_id' => $app['id']]);
            }
            \Yii::error("{$packageCode}对应的appId不存在,抛出异常");
        } catch (AgreementPopUpException $e) {
            \Yii::error("获取用户协议弹出框记录失败,抛出异常，error:{$e->getMessage()}");
        }
    }

    /**
     * addAgreementPopUp 新增用户协议弹出框
     * @date     2019/3/13 16:37
     * @author   周晓坤<1426801685@qq.com>
     * @param $userId
     * @param $packageCode
     * @return AgreementPopUp
     */
    public static function addAgreementPopUp($userId, $packageCode)
    {
        try {
            $apps = Apps::find()
                ->alias('a')
                ->innerJoin(PackageApp::tableName() . ' AS p', "p.app_code = a.app_code")
                ->where(['p.package_code' => $packageCode])
                ->asArray()
                ->one();

            if(empty($apps['id'])){
                \Yii::error("{$packageCode}对应的appId不存在,抛出异常");
                return false;
            }
            $app_id = $apps['id'];
            $agreement = new AgreementPopUp();

            $agreement->user_id    = $userId;
            $agreement->app_id     = $app_id;

            if (!$agreement->save()) {
                \Yii::error("用户：{$userId}插入{$apps['id']}时发生异常");
            }
            return $agreement;
        } catch (BaseException $e) {
            \Yii::error("用户" . \Yii::$app->user->getId() . "新增用户协议弹出框记录失败, error:{$e->getMessage()}");
        }
    }
}