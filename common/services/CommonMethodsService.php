<?php

namespace common\services;

use common\exceptions\BaseException;
use common\exceptions\ProductException;
use common\helpers\Helper;
use common\models\mk\MkProductApiConfig;
use common\models\Orders;
use common\models\Product;
use common\models\RepayPlan;
use common\models\RepayPlanItems;

/**
 * Class CreditProductService
 * @package common\services
 */
class CommonMethodsService
{
    /**
     * 文件描述 产品展示筛选
     * Created On 2019-03-08 20:04
     * Created By heyafei
     * @param $show_type
     * @return array
     * @throws \Exception
     */
    public static function filterShow($show_type)
    {
        // 展示场景
        if($show_type == 1) {
            $online_show = Product::codeCollection(Product::INDEX_BIG_SCENARIO, 5);
        } elseif($show_type == 2) {
            $online_show = Product::codeCollection(Product::INDEX_SMALL_SCENARIO, 5);
        } elseif($show_type == 3) {
            $online_show = Product::codeCollection(Product::LOAN_SCENARIO, 5);
        } else {
            $online_show = Product::codeCollection(Product::REFUSE_SCENARIO, 5);
        }

        // 展示平台
        $device_type = \Yii::$app->request->getHeaders()->get("device-type");
        if($device_type == "ios") {
            $visible_mobile = Product::codeCollection(Product::IOS_VISIBLE, 4);
        } else {
            $visible_mobile = Product::codeCollection(Product::ANDROID_VISIBLE, 3);
        }

        // todo 首复贷/老客新客只做了新老客没有做首复贷
        // 新老客展示
        $product_ids = self::loanedProductIds();
        if ($product_ids) {
            $user_visible = Product::codeCollection(Product::OLD_VISIBLE, 5);
        } else {
            $user_visible = Product::codeCollection(Product::NEW_VISIBLE, 5);
        }
        return [$online_show, $visible_mobile, $user_visible];
    }

    /**
     * 文件描述 用户已经借贷产品ID
     * Created On 2019-03-08 20:12
     * Created By heyafei
     * @return array
     */
    public static function loanedProductIds()
    {
        $product_ids = [];
        $user_id = \Yii::$app->user->getId();
        if($user_id) {
            $order_where = [
                'and',
                ['user_id' => $user_id],
                ["in", 'status', Orders::LOAN_SUCCESS_LIST]
            ];
            $product_ids = Orders::find()->select("product_id")->where($order_where)->column();
        }
        return $product_ids;
    }

    /**
     * 文件描述 对接openAPI接口
     * Created On 2019-03-11 15:44
     * Created By heyafei
     * @param $product_id
     * @param $method
     * @param $request_type
     * @param $params
     * @param $options
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     */
    public static function openApiCurl($product_id, $method, $request_type, $params=[],$options=[])
    {
        $product_api_config = MkProductApiConfig::findOne(['product_id' => $product_id]);
        if(!$product_api_config) {
            throw new BaseException(BaseException::ERROR_CONFIG);
        }
        $args = json_encode($params);
        $call = \Yii::$app->params["openList"][$method];
        $url = $product_api_config->api_url;
        $sign = Helper::setSignKey($product_api_config->api_ua, $product_api_config->api_secret, $call, $args);

        $data =[
            ['name' => 'ua', 'contents' => $product_api_config->api_ua,],
            ['name' => 'call','contents' => $call,],
            ['name' => 'args', 'contents' => $args,],
            ['name' => 'sign', 'contents' => $sign,],
            ['name' => 'timestamp', 'contents' => time(),],
        ];
        return Helper::openCurl($url, $request_type, $data,$options);
    }

    /**
     * 文件描述 发送短信
     * Created On 2019-03-13 14:44
     * Created By heyafei
     * @param $biz_type
     * @param $params
     * @param $phone
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public static function javaSmsApiCurl($biz_type, $params, $phone)
    {
        $data =[
            "bizType" => $biz_type,
            "params" => $params,
            "phone" => $phone
        ];
        Helper::apiCurl(Helper::getApiUrl("sms", "javaApiThird"), "POST", $data, [], "json");
    }

    /**
     * 文件描述 发送push消息
     * Created On 2019-03-13 20:47
     * Created By heyafei
     * @param $user_id
     * @param $msg
     * @param $title
     * @param $device_type
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public static function javaPushApiCurl($user_id, $msg, $title, $device_type = "all")
    {
        $data = [
            "devType" => $device_type,
            "msg" => $msg,
            "title" => $title,
            "userIds" => $user_id,
            "vendorId" => 8
        ];
        Helper::apiCurl(Helper::getApiUrl("push", "javaApiThird"), "POST", $data, [], "json");
    }

    /**
     * 文件描述 订单的最晚还款时间ASC
     * @param $order_sns
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function orderDueTime($order_sns)
    {
        $select = [
            "order_sn" => "rp.order_sn",
            "due_time" => "rpi.due_time",
            "total_amount" => "rpi.total_amount",
            "already_paid" => "rpi.already_paid",
        ];
        $where = [
            "and",
            ['in', 'rp.order_sn', $order_sns],
            ['in', 'rpi.bill_status', [RepayPlanItems::UNREGISTERED, RepayPlanItems::UNPAID]]
        ];
        $list = RepayPlan::find()
            ->alias("rp")
            ->select($select)
            ->leftJoin("mk_repay_plan_items rpi", "rpi.repay_plan_id = rp.id")
            ->filterWhere($where)
            ->orderBy("due_time ASC")
            ->asArray()
            ->all();
        return $list;
    }

}