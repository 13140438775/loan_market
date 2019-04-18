<?php
/**
 * RestFul example.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 2018/3/17 17:02:14
 */

namespace app\controllers;

use app\services\LoginService;
use app\services\ProductService;
use app\services\UserCenterService;
use open\exceptions\RequestException;

/**
 * By heyafei
 * Class ProductController
 * @package app\controllers
 */
class ProductController extends BaseController
{
    use AcceptController;

    public function beforeAction($action) {
        $authorization = \Yii::$app->request->getHeaders()->get('authorization');
        $jwt = \Yii::$app->jwt->loadToken($authorization);
        if(is_null($jwt)){
            return parent::beforeAction($action);
        }
        $user_id = $jwt->getClaim('id');
        $token = \Yii::$app->redis->get(LoginService::getTokenKey($user_id));
        $jwt = \Yii::$app->jwt->loadToken($token);
        if(is_null($jwt)){
            return parent::beforeAction($action);
        }
        \Yii::$app->user->findIdentity($jwt);
        return parent::beforeAction($action);
    }


    /**
     * 文件描述 标签管理
     * Created On 2019-01-29 10:45
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionTagList()
    {
        return ProductService::tagList();
    }

    // banner-list 轮播图
    public function actionBannerList()
    {
        return ProductService::bannerList();
    }

    // 公告列表
    public function actionAnnounceList()
    {
        return ProductService::announceList();
    }

    // 消息类型列表
    public function actionMessageTypeList()
    {
        return ProductService::messageTypeList();
    }

    // 消息列表
    public function actionMessageList()
    {
        $message_type = \Yii::$app->request->get("message_type");
        return ProductService::messageList($message_type);
    }

    // 更新已读消息
    public function actionUpdateMessage()
    {
        $message_id = \Yii::$app->request->get("message_id");
        return ProductService::updateMessage($message_id);
    }

    // 消息详情
    public function actionMessageDetail()
    {
        $message_id = \Yii::$app->request->get("message_id");
        return ProductService::messageDetail($message_id);
    }

    // 小卡位列表
    public function actionProductList()
    {
        $tag_id = \Yii::$app->request->get("tag_id", "0");
        $page = \Yii::$app->request->get("page", "1");
        $page_num = \Yii::$app->request->get("page_num", "6");
        $show_type = \Yii::$app->request->get("show_type", "1"); // 1-首页展示 2-贷超展示
        if(empty($page_num)) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }
        return ProductService::productList($tag_id, $page, $page_num, $show_type);
    }

    // 大卡位
    public function actionProductIndex()
    {
        return ProductService::productIndex();
    }

    // 还款区域
    public function actionOrderIndex()
    {
        return ProductService::orderIndex();
    }

    // 帮助中心
    public function actionHelpCenter()
    {
        return UserCenterService::helpCenter();
    }

    // 是否展示贷超产品
    public function actionIsShowLoanProduct()
    {
        $channel_id = \Yii::$app->request->get("channel_id");
        return ProductService::isShowLoanProduct($channel_id);
    }
    
}