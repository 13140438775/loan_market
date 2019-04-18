<?php

namespace app\services;

use common\exceptions\BaseException;
use common\exceptions\OrdersException;
use common\exceptions\ProductException;
use common\models\Orders;
use common\models\ProductApiConfig;
use common\models\ProductBank;
use common\models\UserBank;
use common\services\CommonMethodsService;
use common\helpers\Helper;
use common\services\HelpService;
use yii\helpers\ArrayHelper;

/**
 * 订单数据操作
 * Class OrdersService
 * @package app\services
 */
class BankService extends BaseService
{
    // 还款卡
    const REPAY_CARD = 2;
    const SUCCESS = 200;

    static $bank_code_set = [
        "中国工商银行" => [
            "bank_code" => "ICBC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/001.png"
        ],
        "中国农业银行" => [
            "bank_code" => "ABC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/002.png"
        ],
        "中国银行"    => [
            "bank_code" => "BOC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/003.png"
        ],
        "中国建设银行" => [
            "bank_code" => "CCB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/004.png"
        ],
        "中国交通银行" => [
            "bank_code" => "BCOM",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/005.png"
        ],
        "中国民生银行" => [
            "bank_code" => "CMBC",
            "bank_icon" => ""
        ],
        "招商银行"    => [
            "bank_code" => "CMB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/006.png"
        ],
        "邮政储蓄银行" => [
            "bank_code" => "PSBC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/007.png"
        ],
        "平安银行" => [
            "bank_code" => "PAB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/008.png"
        ],
        "中信银行" => [
            "bank_code" => "CITIC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/010.png"
        ],
        "中国光大银行" => [
            "bank_code" => "CEB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/012.png"
        ],
        "兴业银行" => [
            "bank_code" => "CIB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/011.png"
        ],
        "广东发展银行" => [
            "bank_code" => "GDB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/013.png"
        ],
        "华夏银行" => [
            "bank_code" => "HXB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/009.png"
        ],
        "上海浦东发展银行" => [
            "bank_code" => "SPDB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/014.png"
        ],
        "北京银行" => [
            "bank_code" => "BOB",
            "bank_icon" => ""
        ],
        "南京银行" => [
            "bank_code" => "NJCB",
            "bank_icon" => ""
        ],
        "杭州商业银行" => [
            "bank_code" => "HZB",
            "bank_icon" => ""
        ],
        "宁波银行" => [
            "bank_code" => "NBCB",
            "bank_icon" => ""
        ],
        "汉口银行" => [
            "bank_code" => "HKBANK",
            "bank_icon" => ""
        ],
        "浙商银行" => [
            "bank_code" => "CZB",
            "bank_icon" => ""
        ],
        "徽商银行" => [
            "bank_code" => "HSB",
            "bank_icon" => ""
        ],
        "渤海银行" => [
            "bank_code" => "CBHB",
            "bank_icon" => ""
        ],
    ];


    // 文件描述 未绑卡的订单
    public static function getUnbindCardOrder($product_id)
    {
        $user_id = \Yii::$app->user->getId();
        $where =[
            'and',
            [
                'product_id' => $product_id,
                'user_id' => $user_id,
                'twice_msg' => "success"
            ],
            ['!=', 'status', Orders::FINISH]
        ];
        $res = Orders::find()->filterWhere($where)->asArray()->one();
        if(!$res) {
            throw new OrdersException(OrdersException::ORDER_FAIL);
        }
        return $res;
    }

    // 用户信息
    public static function userInfo()
    {
        $user = \Yii::$app->user->getIdentity();
        return [
            "user_name" => $user->real_name,
            "show_card_id" => HelpService::starReplace($user->card_id),
            "user_idcard" => $user->card_id,
            "user_phone" => $user->user_phone
        ];
    }

    /**
     * 文件描述 绑卡页面接口
     * Created On 2019-03-19 20:18
     * Created By heyafei
     * @param $product_id
     * @return array
     * @throws ProductException
     * @throws \Throwable
     */
    public static function bindCardPage($product_id)
    {
        $product_api_config = ProductApiConfig::findOne(['product_id' => $product_id]);
        if(empty($product_api_config)) {
            throw new ProductException(ProductException::NOT_FOUND);
        }
        // 用户在该产品下是否有银行卡
        $product_bank_list = self::userProductCard($product_id);

        // api 绑卡
        if($product_api_config->bind_card_mode == ProductApiConfig::BIND_CARD_MODE_API) {
            if($product_bank_list) {
                // 用户在该产品下有银行卡
                $product_bank_info = self::apiBindCard($product_bank_list, $product_id);
                return [
                    "h5_url" => "",
                    "can_replace_card" => $product_api_config->can_replace_card, // 0-不支持 1-支持
                    "card_info" => $product_bank_info
                ];
            } else {
                // 用户在该产品下没有银行卡
                return [
                    "h5_url" => "",
                    "can_replace_card" => $product_api_config->can_replace_card, // 0-不支持 1-支持
                    "card_info" => []
                ];
            }
        } else {
        // 非api绑卡
            if($product_bank_list) {
                return [
                    "h5_url" => "",
                    "can_replace_card" => $product_api_config->can_replace_card, // 0-不支持 1-支持
                    "card_info" => current($product_bank_list)
                ];
            } else {
                $h5_url = self::h5BindCard($product_api_config, $product_id);
                $return_url = $_SERVER['SERVER_NAME']."/bindCard/result";
                return [
                    "h5_url" => $h5_url."&{$return_url}",
                    "can_replace_card" => "", // 0-不支持 1-支持
                    "card_info" => []
                ];
            }
        }
    }


    // 用户在该产品的银行卡是否有在商户中的支持卡列表中的卡
    public static function apiBindCard($product_bank_list, $product_id)
    {
        $res['response'] = CommonMethodsService::openApiCurl($product_id, "getBankList", "POST");
        $user_card_codes = ArrayHelper::getColumn($product_bank_list, "bank_code");
        $agency_card_codes = ArrayHelper::getColumn($res["response"], "bank_code");

        $same_card_codes = array_intersect($user_card_codes, $agency_card_codes); // 支持的卡列表
        foreach($product_bank_list AS $key => $val) {
            if(in_array($val['bank_code'], $same_card_codes)) {
                unset($product_bank_list[$key]);
            }
        }
        return current($product_bank_list);
    }

    // 非API绑卡
    public static function h5BindCard(ProductApiConfig $product_api_config, $product_id)
    {
        $user = \Yii::$app->user->getIdentity();
        $orders = self::getUnbindCardOrder($product_id);
        $sign = Helper::setSignKey($product_api_config->api_ua, $product_api_config->api_secret, "", "");
        if($product_api_config->bind_card_mode == ProductApiConfig::BIND_CARD_MODE_H5) {
            $params = [
                "order_sn" => $orders->order_sn,
                "auth_type" => 1, // 认证类型 1:绑卡,2:还款
                "return_url" => "return_url=http://".$_SERVER['HTTP_HOST']."/bindCard/result",
            ];
            $res = CommonMethodsService::openApiCurl($product_id, "h5Url", "POST", $params);
            $h5_url = $res['response']['auth_url'];
        } else {
            $h5_url = $product_api_config->bind_card_h5_url;
        }
        return "{$h5_url}?order_sn={$orders->order_sn}&user_name={$user->real_name}&user_phone={$user->user_phone}&user_idcard={$user->card_id}&sign={$sign}";
    }

    // 用户在该产品下是否有银行卡
    public static function userProductCard($product_id)
    {
        $user_id = \Yii::$app->user->getId();
        $select = [
            "bank_code" => "ub.bank_code",
            "card_number" => "ub.card_number",
            "bank_name" => "ub.bank_name",
            "card_phone" => "ub.card_phone",
            "is_main" => "pb.is_main",
        ];
        $res = ProductBank::find()
            ->select($select)
            ->alias("pb")
            ->innerJoin("mk_user_bank ub", "ub.id = pb.bank_id")
            ->filterWhere(['pb.product_id' => $product_id, 'ub.user_id' => $user_id])
            ->orderBy("pb.updated_at DESC")
            ->asArray()
            ->all();
        return $res;
    }

    /**
     * 文件描述 绑定银行卡
     * Created On 2019-03-19 09:58
     * Created By heyafei
     * @param $product_id
     * @param $params
     * @param $data
     * @param $use_type
     * @throws \Throwable
     */
    public static function bindCard($product_id, $params, $data, $use_type)
    {
        \Yii::$app->db->transaction(function ($db) use ($product_id, $params, $data, $use_type) {
            // 用户银行卡表
            $user_bank = UserBank::findOne(["card_number" => $params['card_number']]);
            if(!$user_bank) {
                $user_bank = new UserBank();
                $user_bank->user_id = \Yii::$app->user->getId();
                $user_bank->bank_name = $data['bankName'];
                $user_bank->bank_code = $params['bank_code'];
                $user_bank->bank_icon = $data['bankIcon'];
                $user_bank->card_number = $params['card_number'];
                $user_bank->card_phone = $params['card_phone'];
                $user_bank->card_type = $params['card_type'];
                if(!$user_bank->save()) {
                    throw new BaseException(BaseException::SAVE_FAIL, json_encode($user_bank->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }

            // 订单表
            $orders = Orders::findOne(['order_sn' => $params['order_sn']]);
            if($orders) {
                if($use_type == self::REPAY_CARD) {
                    $orders->repay_bank_id = $user_bank->id;
                } else {
                    $orders->loan_bank_id = $user_bank->id;
                    $orders->repay_bank_id = $user_bank->id;
                    $orders->status = Orders::PENDING; // 待审核
                }
                if(!$orders->save()) {
                    throw new BaseException(BaseException::SAVE_FAIL, json_encode($orders->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }

            // 产品银行表
            ProductBank::updateAll(['is_main' => ProductBank::NO_MAIN], ['user_id' => $user_bank->user_id, 'product_id' => $orders->product_id]);
            $product_bank = ProductBank::findOne(['bank_id' => $user_bank->id, 'product_id' => $product_id]);
            if(!$product_bank) {
                $product_bank = new ProductBank();
                $product_bank->bank_id = $user_bank->id;
                $product_bank->product_id = $product_id;
            }
            $product_bank->is_main = ProductBank::IS_MAIN;
            if(!$product_bank->save()) {
                throw new BaseException(BaseException::SAVE_FAIL, json_encode($product_bank->getErrors(), JSON_UNESCAPED_UNICODE));
            }

            Helper::apiCurl(Helper::getApiUrl("bindCard", "javaApiSecond"), "POST", $data, [], "json");
            CommonMethodsService::openApiCurl($product_id, "applyBindCard", "POST", $params);
        });
    }

    /**
     * 文件描述 状态更新为待审核
     * Created On 2019-03-20 10:25
     * Created By heyafei
     * @param $order_sn
     * @param $bind_status
     */
    public static function h5BindCardCallback($order_sn, $bind_status)
    {
        if($bind_status == self::SUCCESS) {
            Orders::updateAll(['status' => Orders::PENDING], ['order_sn' => $order_sn]);
        }
    }
}

