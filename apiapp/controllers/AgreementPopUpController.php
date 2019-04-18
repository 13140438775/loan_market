<?php

namespace app\controllers;

use yii;
use app\services\AgreementPopUpService;

/**
 * By heyafei
 * Class AgreementPopUpController
 * @package app\controllers
 */
class AgreementPopUpController extends BaseController 
{
    // 查询用户协议弹出框记录
    public function actionGetAgreementPopUp()
    {
        $user = \Yii::$app->user->getIdentity();
        $packageCode = \Yii::$app->request->getHeaders()->get('package-name');
        $info = AgreementPopUpService::getAgreementPopUp($user['id'], $packageCode);
        $data = [];
        if(empty($info)){
            $data = [
                ['name' => '用户注册协议', 'link' => 'http://newpro.mykuaipai.com/agreement/protocolRegister'],
                ['name' => '隐私政策及授权使用协议', 'link' => 'http://newpro.mykuaipai.com/agreement/protocolPrivacy'],
                ['name' => '对第三方机构的信息收集授权协议', 'link' => 'http://newpro.mykuaipai.com/agreement/protocolAuthorization'],
            ];
        }
        return $data;
    }

    // 插入用户协议弹出框记录
    public function actionAddAgreementPopUp()
    {
        $user = \Yii::$app->user->getIdentity();
        $packageCode = \Yii::$app->request->getHeaders()->get('package-name');

        return AgreementPopUpService::addAgreementPopUp($user['id'], $packageCode);
    }
}