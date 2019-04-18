<?php
namespace api\controllers;

use api\services\CreditProductService;
use common\exceptions\RequestException;

/**
 * Site controller
 */
class CreditProductController extends BaseController
{

    /**
     * 文件描述 产品列表
     * Created On 2019-02-21 20:21
     * Created By heyafei
     * @return array
     * @throws RequestException
     */
    public function actionIndex()
    {
        $tag_id = \Yii::$app->request->get("tag_id", "0");
        $page = \Yii::$app->request->get("page", "1");
        $page_num = \Yii::$app->request->get("page_num", "6");
        if(empty($page_num)) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }
        return CreditProductService::productList($tag_id, $page, $page_num);
    }

    /**
     * 文件描述 热门产品列表
     * Created On 2019-02-21 20:21
     * Created By heyafei
     * @return array
     * @throws RequestException
     */
    public function actionHotProductList()
    {
        $tag_id = \Yii::$app->request->get("tag_id", "0");
        $page = \Yii::$app->request->get("page", "1");
        $page_num = \Yii::$app->request->get("page_num", "6");
        if(empty($page_num)) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }
        return CreditProductService::hotProductList($tag_id, $page, $page_num);
    }

    /**
     * 文件描述 产品详情
     * Created On 2019-01-29 10:45
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionView()
    {
        $product_id = \Yii::$app->request->get('id', "0");
        return CreditProductService::productView($product_id);
    }

    /**
     * 文件描述 标签管理
     * Created On 2019-01-29 10:45
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionTagList()
    {
        return CreditProductService::tagList();
    }


}
