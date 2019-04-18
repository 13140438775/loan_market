<?php

namespace app\controllers;

use app\services\UserService;
use common\exceptions\UserAcceptException;

/**
 * Class BaseController
 * @package api\controllers
 */
class UserController extends BaseController
{
    /**
     * 姓名，身份证二要素认证
     * @return mixed
     * @throws UserAcceptException
     */
    public function actionValidateIdCard()
    {
        $cardNo = strtoupper(\Yii::$app->request->post('cardNo'));
        $name = \Yii::$app->request->post('name');

        if(empty($name)){
            throw new UserAcceptException(UserAcceptException::NO_NAME);
        }
        if(empty($cardNo)){
            throw new UserAcceptException(UserAcceptException::NO_ID_CARD);
        }

        if(!UserService::getInstance()->validateIdcard($cardNo)){
            throw new UserAcceptException(UserAcceptException::ID_CARD_ERROR);
        }

        return UserService::getInstance()->CheckUserBasic($name,$cardNo);
    }

    /**
     * 定期清理身份认证信息，基础平台通知
     */
    public function actionCleanIdCard(){}
}
