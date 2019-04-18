<?php

namespace app\services;

use common\exceptions\RequestException;
use common\models\ChannelConfig;
use common\models\mk\MkAnnounce;
use common\models\mk\MkBannerInfo;
use common\models\mk\MkProductProperty;
use common\models\mk\MkPushMessage;
use common\models\Orders;
use common\models\Product;
use common\models\ProductTag;
use common\services\CommonMethodsService;
use common\services\CommonModelService;
use common\services\VersionService;
use yii\helpers\ArrayHelper;

/**
 * Class CreditProductService
 * @package common\services
 */
class ProductService extends BaseService
{
    /**
     * 文件描述 标签列表
     * Created On 2019-01-28 20:23
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function tagList()
    {
        $where = [
            'is_enable' => self::IS_VALID,
            'is_valid' => self::IS_VALID
        ];
        $tags = ProductTag::find()->where($where)->limit(4)->orderBy("sort")->asArray()->all();
        array_walk($tags, function (&$item) {
            $item['tag_icon'] = $item['tag_icon'] ? \Yii::$app->params['oss']['url_prefix'] . $item['tag_icon'] : '';
            $item['tag_img'] = $item['tag_icon'] ? \Yii::$app->params['oss']['url_prefix'] . $item['tag_img'] : '';
        });
        return $tags;
    }

    /**
     * 文件描述 banner图
     * Created On 2019-03-07 15:17
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function bannerList()
    {
        return MkBannerInfo::find()
            ->select(['img', 'url'])
            ->where(['<=', 'begin_time', time()])
            ->andWhere(['>=', 'end_time', time()])
            ->orderBy("sort DESC")
            ->asArray()
            ->all();
    }

    /**
     * 文件描述 公告列表
     * Created On 2019-03-07 15:22
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function announceList()
    {
        return MkAnnounce::find()->select('content')->orderBy("sort DESC")->asArray()->all();
    }

    /**
     * 文件描述 消息类型列表
     * Created On 2019-03-07 16:20
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function messageTypeList()
    {
        $model = new MkPushMessage();
        $message_list = $model::find()
            ->select("message_type, COUNT(id) AS msg_count, push_msg, created_at")
            ->where(['is_read' => $model::NO_READ])
            ->andFilterWhere(['in', 'user_id', [0, \Yii::$app->user->id]])
            ->groupBy("message_type")
            ->orderBy("message_type DESC, id DESC")
            ->asArray()
            ->all();
        foreach ($message_list AS &$val) {
            $val['message_type_name'] = $model->message_type_set[$val['message_type']];
            $val['created_at'] = date("Y-m-d", $val['created_at']);
        }
        return $message_list;
    }

    /**
     * 文件描述 消息列表
     * Created On 2019-03-07 16:39
     * Created By heyafei
     * @param $message_type
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function messageList($message_type)
    {
        return MkPushMessage::find()
            ->select(['id', 'title', 'push_msg', 'is_read', 'created_at'])
            ->where(['message_type' => $message_type])
            ->andFilterWhere(['in', 'user_id', [0, \Yii::$app->user->id]])
            ->asArray()
            ->all();
    }

    /**
     * 文件描述 更新已读消息
     * Created On 2019-03-07 16:48
     * Created By heyafei
     * @param $message_id
     * @return int
     */
    public static function updateMessage($message_id)
    {
        return MkPushMessage::updateAll(['is_read' => MkPushMessage::IS_READ], ['id' => $message_id]);
    }

    /**
     * 文件描述 消息详情
     * Created On 2019-03-07 20:58
     * Created By heyafei
     * @param $message_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function messageDetail($message_id)
    {
        $msg_detail =  MkPushMessage::find()
            ->select(['title', 'push_msg', 'created_at'])
            ->where(['id' => $message_id])
            ->asArray()
            ->one();
        if($msg_detail) {
            $msg_detail['created_at'] = date("Y-m-d H:i:s", $msg_detail['created_at']);
        }
        return $msg_detail;
    }

    /**
     * 文件描述 小卡位列表
     * Created On 2019-03-08 19:48
     * Created By heyafei
     * @param $tag_id
     * @param $page
     * @param $page_num
     * @param $show_type
     * @return array
     * @throws \Exception
     */
    public static function productList($tag_id, $page, $page_num, $show_type)
    {

        list($online_show, $visible_mobile, $user_visible) = CommonMethodsService::filterShow($show_type);

        $where = [];
        $select = [
            'product_id' => "p.id",
            'product_name' => "p.name",
            'show_name' => "p.show_name",
            'logo_url' => "p.logo_url",
            'description' => "p.description",
            'show_interest_desc' => "p.show_interest_desc",
            'show_amount_range' => "p.show_amount_range",
            'sort_min_loan_time' => "p.sort_min_loan_time",
            'sort_min_loan_time_type' => "p.sort_min_loan_time_type",
            'show_avg_term' => "p.show_avg_term",
            'enable_count_limit' => "p.enable_count_limit",
            'is_time_sharing' => "p.is_time_sharing",
            'limit_begin_time' => "p.limit_begin_time",
            'limit_end_time' => "p.limit_end_time",
            'show_tag_id' => "p.show_tag_id",
            'weight' => "p.weight"
        ];
        if($tag_id) $where['pat.tag_id'] = $tag_id;

        $model = Product::find()
            ->alias("p")
            ->select($select)
            ->innerJoin("mk_product_assoc_tag pat", "pat.product_id = p.id")
            ->filterWhere($where)
//            ->andFilterWhere(['in', 'visible', $user_visible])
//            ->andFilterWhere(['in', 'visible_mobile', $visible_mobile])
//            ->andFilterWhere(['in', 'online_scenario', $online_show])
            ->distinct(true);
        $total_count = $model->count();

        $total_page = ceil($total_count / $page_num);
        $product_list = $model->limit($page_num)
            ->offset(($page - 1) * $page_num)
            ->orderBy("weight DESC")
            ->asArray()
            ->all();
        $product_ids = ArrayHelper::getColumn($product_list, "product_id");
        $show_tag_ids = ArrayHelper::getColumn($product_list, "show_tag_id");

        // 数据拼接
        $product_property_list = MkProductProperty::find()->select(['product_id', 'is_show_fee_txt', 'is_show_desc_entry'])
            ->filterWhere(['in', 'product_id', $product_ids])->indexBy("product_id")->asArray()->all();
        $tag_list = ProductTag::find()->select(['id AS show_tag_id', 'tag_name', 'tag_img'])
            ->filterWhere(['in', 'id', $show_tag_ids])->indexBy("show_tag_id")->asArray()->all();
        foreach ($product_list AS &$val) {
            if(isset($product_property_list[$val['product_id']])) {
                $product_temp = $product_property_list[$val['product_id']];
            } else {
                $product_temp['is_show_fee_txt'] = $product_temp['is_show_desc_entry'] = 0;
            }
            if(isset($tag_list[$val['show_tag_id']])) {
                $tag_temp = $tag_list[$val['show_tag_id']];
            } else {
                $tag_temp['tag_name'] = $tag_temp['tag_img'] = '';
            }
            $val = array_merge($val, $product_temp, $tag_temp);
        }

        // 权重加权
        array_walk($product_list, function ($item){
            if($item['enable_count_limit'] && $item['is_time_sharing']) {
                if($item['limit_begin_time'] > time() || $item['limit_end_time'] < time()) $item['weight'] = 0;
            }
        });

        // 排序
        uasort($product_list, function ($a, $b){
            if($a['weight'] == $b['weight']) return 0;
            return $a['weight'] > $b['weight'] ? 1: -1;
        });

        return [
            'total_count' => $total_count,
            'total_page' => $total_page,
            'staff_list' => $product_list,
        ];
    }


    /**
     * 文件描述 大卡位展示
     * Created On 2019-03-08 20:05
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord|null
     * @throws \Exception
     */
    public static function productIndex()
    {
        $show_type = 1;
        list($online_show, $visible_mobile, $user_visible) = CommonMethodsService::filterShow($show_type);
        $product_ids = CommonMethodsService::loanedProductIds();

        $online_show = Product::INDEX_BIG_SCENARIO;
        $select = [
            'product_id' => "id",
            'product_name' => "name",
            'show_name' => "show_name",
            'logo_url' => "logo_url",
            'description' => "description",
            'show_interest_desc' => "show_interest_desc",
            'show_amount_range' => "show_amount_range",
            'sort_min_loan_time' => "sort_min_loan_time",
            'show_avg_term' => "show_avg_term",
            'enable_count_limit' => "enable_count_limit",
            'is_time_sharing' => "is_time_sharing",
            'limit_begin_time' => "limit_begin_time",
            'limit_end_time' => "limit_end_time",
            'show_tag_id' => "show_tag_id",
            'weight' => "weight"
        ];
        $product_list = Product::find()
            ->select($select)
//            ->filterWhere(['not exists', 'id', $product_ids])
//            ->andFilterWhere(['in', 'visible', $user_visible])
//            ->andFilterWhere(['in', 'visible_mobile', $visible_mobile])
//            ->andFilterWhere(['in', 'online_scenario', $online_show])
            ->orderBy("weight")
            ->asArray()
            ->all();

        // 权重加权
        array_walk($product_list, function ($item){
            if($item['enable_count_limit'] && $item['is_time_sharing']) {
                if($item['limit_begin_time'] > time() || $item['limit_end_time'] < time()) $item['weight'] = 0;
            }
        });

        // 排序
        uasort($product_list, function ($a, $b){
            if($a['weight'] == $b['weight']) return 0;
            return $a['weight'] > $b['weight'] ? 1: -1;
        });


        // 取第一个
        $product_index = [];
        if($product_list) list($product_index) = $product_list;
        return $product_index;
    }

    /**
     * 文件描述 贷还款订单
     * Created On 2019-03-11 10:35
     * Created By heyafei
     * @return array|\yii\db\ActiveRecord
     */
    public static function orderIndex()
    {
        $user_id = \Yii::$app->user->getId();
        if(!$user_id) return [];

        $select =[
            "product_id" => "mp.id",
            "show_name" => "mp.show_name",
            "logo_url" => "mp.logo_url",
            "weight" => "mp.weight",
            "order_id" => "o.id",
            "order_sn" => "o.order_sn",
            "loan_amount" => "o.loan_amount",
            "loan_term" => "o.loan_term",
            "term_type" => "o.term_type"
        ];
        $where =[
            'user_id' => $user_id
        ];

        $order_list = Orders::find()
            ->alias("o")
            ->select($select)
            ->leftJoin("mk_product mp", "mp.id = o.product_id")
            ->filterWhere($where)
            ->asArray()
            ->all();
        $order_sns = ArrayHelper::getColumn($order_list, "order_sn");
        $list = CommonMethodsService::orderDueTime($order_sns);
        if(empty($list)) return [];

        list($order_due_time) = $list;
        $order_due_time['repay_time'] = ($order_due_time['due_time'] - time()) / 86400;
        if($order_due_time['repay_time'] > 0) {
            $order_due_time['repay_time'] = "离还款还有".ceil($order_due_time['repay_time'])."天";
        } else {
            $order_due_time['repay_time'] = "已逾期".ceil(abs($order_due_time['repay_time']))."天";
        }

        foreach($order_list AS $val) {
            if($val['order_sn'] == $order_due_time["order_sn"]) {
                return array_merge($val, $order_due_time);
            }
        }
    }

    /**
     * 文件描述 是否展示贷超产品
     * Created On 2019-03-24 14:25
     * Created By heyafei
     * @param $channel_id
     * @return array
     * @throws RequestException
     * @throws \Throwable
     */
    public static function isShowLoanProduct($channel_id)
    {
        $channel = ChannelConfig::findOne($channel_id);
        if(!$channel) {
            throw new RequestException(RequestException::VALIDATE_FAIL);
        }
        // 判断是否是通用包
        if($channel->is_general_package == ChannelConfig::GENERAL_PACKAGE) {
            return [
                'is_show' => "all"
            ];
        }

        $user = \Yii::$app->user->getIdentity();
        // 是否是登陆用户
        if($user) {
            $sign_in_begin_version = $channel->sign_in_begin_version;
            $sign_in_end_version = $channel->sign_in_end_version;
            // 开始版本
            if($sign_in_begin_version) {
                $begin_version_res = VersionService::versionCompare($sign_in_begin_version, ">=");
            } else {
                $begin_version_res = false;
            }
            // 结束版本
            if($sign_in_end_version) {
                $end_version_res = VersionService::versionCompare($sign_in_end_version, "<=");
            } else {
                $end_version_res = false;
            }

            // 已登录不显示贷超版本
            if($begin_version_res && $end_version_res) {
                if(CommonModelService::RepayUser($user->id)) {
                    return [
                        'is_show' => "/product/order-index"
                    ];
                }
                return [
                    'is_show' => "/product/product-index"
                ];
            }

            // 是否只对放款用户展示
            if($channel->is_show_loan_user){
                // 不是放款用户
                if(!CommonModelService::LoanSuccess($user->id)) {
                    return [
                        'is_show' => "/product/product-index"
                    ];
                }
            }

            // 获取用户的登陆时间
            $login_time = CommonModelService::UserLoginTime($user->id);
            $time = time() - strtotime($login_time);
            // 登录用户指定时间展示
            $show_day = $channel->show_day;

            // 登陆用户指定时间>0并且登陆时间<指定的天数
            if($show_day && ($time < $show_day * 86400) ) {
                if(CommonModelService::RepayUser($user->id)) {
                    return [
                        'is_show' => "/product/order-index"
                    ];
                }
                return [
                    'is_show' => "/product/product-index"
                ];
            }
        } else {
            $unsign_in_begin_version = $channel->unsign_in_begin_version;
            $unsign_in_end_version = $channel->unsign_in_end_version;
            // 开始版本
            if($unsign_in_begin_version) {
                $begin_version_res = VersionService::versionCompare($unsign_in_begin_version, ">=");
            } else {
                $begin_version_res = false;
            }
            // 结束版本
            if($unsign_in_end_version) {
                $end_version_res = VersionService::versionCompare($unsign_in_end_version, "<=");
            } else {
                $end_version_res = false;
            }

            // 未登录不显示贷超版本
            if($begin_version_res && $end_version_res) {
                return [
                    'is_show' => "/product/product-index"
                ];
            }
        }
        return [
            "is_show" => "all"
        ];
    }

}