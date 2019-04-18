<?php

namespace open\services;

use Yii;
use common\helpers\Helper;
use common\models\ProductBank;
use common\models\UserBank;
use common\services\CommonMethodsService;
use common\services\OrderStatusNoticeService;
use common\services\RepayPlanFeedbackService;
use common\models\Orders;
use open\exceptions\BaseException;
use open\exceptions\OrderException;
use common\services\OrderNoticeService;
use common\models\LoanUsers;
use common\models\Product;
use common\models\UsersBlack;
use common\services\ContractService;
use common\models\ProductApiConfig;

/**
 * orderService 订单服务
 */
class OrderService extends BaseService
{
    // 还款卡
    const REPAY_CARD = 2;

    static $success = 200;

    static $bank_code_set = [
        "中国工商银行"   => [
            "bank_code" => "ICBC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/001.png"
        ],
        "中国农业银行"   => [
            "bank_code" => "ABC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/002.png"
        ],
        "中国银行"     => [
            "bank_code" => "BOC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/003.png"
        ],
        "中国建设银行"   => [
            "bank_code" => "CCB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/004.png"
        ],
        "中国交通银行"   => [
            "bank_code" => "BCOM",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/005.png"
        ],
        "中国民生银行"   => [
            "bank_code" => "CMBC",
            "bank_icon" => ""
        ],
        "招商银行"     => [
            "bank_code" => "CMB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/006.png"
        ],
        "邮政储蓄银行"   => [
            "bank_code" => "PSBC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/007.png"
        ],
        "平安银行"     => [
            "bank_code" => "PAB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/008.png"
        ],
        "中信银行"     => [
            "bank_code" => "CITIC",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/010.png"
        ],
        "中国光大银行"   => [
            "bank_code" => "CEB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/012.png"
        ],
        "兴业银行"     => [
            "bank_code" => "CIB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/011.png"
        ],
        "广东发展银行"   => [
            "bank_code" => "GDB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/013.png"
        ],
        "华夏银行"     => [
            "bank_code" => "HXB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/009.png"
        ],
        "上海浦东发展银行" => [
            "bank_code" => "SPDB",
            "bank_icon" => "https://appsite-oss.oss-cn-hangzhou.aliyuncs.com/common/web/images/bank/014.png"
        ],
        "北京银行"     => [
            "bank_code" => "BOB",
            "bank_icon" => ""
        ],
        "南京银行"     => [
            "bank_code" => "NJCB",
            "bank_icon" => ""
        ],
        "杭州商业银行"   => [
            "bank_code" => "HZB",
            "bank_icon" => ""
        ],
        "宁波银行"     => [
            "bank_code" => "NBCB",
            "bank_icon" => ""
        ],
        "汉口银行"     => [
            "bank_code" => "HKBANK",
            "bank_icon" => ""
        ],
        "浙商银行"     => [
            "bank_code" => "CZB",
            "bank_icon" => ""
        ],
        "徽商银行"     => [
            "bank_code" => "HSB",
            "bank_icon" => ""
        ],
        "渤海银行"     => [
            "bank_code" => "CBHB",
            "bank_icon" => ""
        ],
    ];

    /**
     * lendingFeedback 订单放款结果回调
     * @date     2019/3/14 11:40
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     * @throws OrderException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function lendingFeedback($data)
    {
        try {
            // 判断订单和订单状态 保存数据 通知用户
            $order_sn = $data['order_sn'];
            $order    = Orders::findOne(['order_sn' => $order_sn]);
            if ($order === null) {
                \Yii::error("该订单不存在.订单号是:{$order_sn},抛出异常");
                throw new OrderException(OrderException::ORDER_NOT_EXIT);
            }
            if ($order->status != Orders::WAITING_LOAN) {
                \Yii::error("该订单状态非待放款的状态,状态是:{$order->status},抛出异常");
                throw new OrderException(OrderException::ORDER_STATUS_FAIL);
            }
            $user_id    = $order->user_id;
            $product_id = $order->product_id;
            $user       = LoanUsers::findOne($user_id);
            if ($user == null) {
                \Yii::error("用户不存在,用户id:{$user_id},抛出异常");
                throw new OrderException(OrderException::USER_NOT_EXIT);
            }
            $product = Product::findOne($product_id);
            if ($product == null) {
                \Yii::error("产品不存在,产品id:{$product_id},抛出异常");
                throw new OrderException(OrderException::PRODUCT_NOT_EXIT);
            }
            $params['user_id']      = $user_id;
            $params['phone']        = $user->user_phone;
            $params['user_name']    = $user->real_name;
            $params['product_name'] = empty($product->show_name) ? $product->name : $product->show_name;
            $params['amount']       = $order->loan_amount / 100;
            if ($data['lending_status'] == 200) {
                $order->status = Orders::LOAN_SUCCESS;

                // 保存用户协议服务
                $contact = ContractService::getOrderContracts($product_id, $order_sn);
                if ($contact['status'] == 1) {
                    $order->contact_info = json_encode($contact['response']);
                }
                if (!$order->save()) {
                    \Yii::error("更新订单失败,订单号是:{$order_sn},抛出异常");
                    throw new OrderException(OrderException::SAVE_ORDER_FAIL);
                }
                $params['template_id'] = OrderNoticeService::LOAN_SUCCESS;
                // 给用户发放款成功消息
                $service = \Yii::$container->get("OrderStatusNoticeService");
                \Yii::$app->redis->Lpush($service->getOrderStatusNoticeKey(), json_encode($params));
            } else if ($data['lending_status'] == 401) {
                $order->status = Orders::LOAN_FAIL;
                $order->remark = $data['fail_reason'];
                if (!$order->save()) {
                    \Yii::error("更新订单失败,订单号是:{$order_sn},抛出异常");
                    throw new OrderException(OrderException::SAVE_ORDER_FAIL);
                }
                $params['template_id'] = OrderNoticeService::LOAN_FAIL;
                // 给用户发放款失败消息
                $service = \Yii::$container->get("OrderStatusNoticeService");
                \Yii::$app->redis->Lpush($service->getOrderStatusNoticeKey(), json_encode($params));
            } else {
                throw new OrderException(OrderException::LENDING_STATUS_FAIL);
            }

        } catch (\Exception $e) {
            throw new OrderException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * approveFeedback 订单审批结果回调
     * @date     2019/3/14 11:40
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     * @throws OrderException
     */
    public static function approveFeedback($data)
    {
        try {
            // 判断审核结果 后台配置 改状态
            $order_sn = $data['order_sn'];
            $amount   = $data['approve_amount'];
            $term     = $data['approve_term'];
            $type     = $data['term_type'];
            $order    = Orders::findOne(['order_sn' => $order_sn]);
            if ($order === null) {
                throw new OrderException(OrderException::ORDER_NOT_EXIT);
            }
            if ($order->status != Orders::PENDING) {
                throw new OrderException(OrderException::ORDER_STATUS_FAIL);
            }
            $user_id    = $order->user_id;
            $product_id = $order->product_id;
            $user       = LoanUsers::findOne($user_id);
            if ($user == null) {
                throw new OrderException(OrderException::USER_NOT_EXIT);
            }
            $product = Product::findOne($product_id);
            if ($product == null) {
                throw new OrderException(OrderException::PRODUCT_NOT_EXIT);
            }
            $params['user_id']      = $user_id;
            $params['phone']        = $user->user_phone;
            $params['user_name']    = $user->real_name;
            $params['product_name'] = empty($product->show_name) ? $product->name : $product->show_name;
            $params['amount']       = $order->loan_amount / 100;
            if ($data['approve_status'] == 200) {
                $productConfig = ProductApiConfig::findOne(['product_id' => $product_id]);
                if ($productConfig === null) {
                    throw new OrderException(OrderException::APPROVE_STATUS_FAIL);
                }
                // 读取后台配置:审核额度是否可变更 是否有商城
                if (!$productConfig->is_update_audit_limit) {
                    if ($order->loan_amount != $amount) {
                        throw new OrderException(OrderException::ORDER_AMOUNT_FAIL);
                    }
                    if ($order->loan_term != $term) {
                        throw new OrderException(OrderException::LOAN_TERM_FAIL);
                    }
                    if ($order->term_type != $type) {
                        throw new OrderException(OrderException::TERM_TYPE_FAIL);
                    }
                }
                if ($productConfig->is_market) {
                    throw new OrderException(OrderException::PRODUCT_CONFIG_IS_MARKET_FAIL);
                }
                // 修改待签约的金额与期限 类型
                $order->confirm_amount    = $amount;
                $order->confirm_term      = $term;
                $order->confirm_term_type = $type;
                // 订单状态更改为待签约
                $order->status = Orders::WAITING_SIGN;
                if (!$order->save()) {
                    throw new OrderException(OrderException::SAVE_ORDER_FAIL);
                }
                $params['template_id'] = OrderNoticeService::REVIEW_SUCCESS;
                // 给用户发送审核成功的通知
                $service = \Yii::$container->get("OrderStatusNoticeService");
                \Yii::$app->redis->Lpush($service->getOrderStatusNoticeKey(), json_encode($params));
            } else if ($data['approve_status'] == 403) {
                // 失败则改订单状态, 增加黑名单
                $order->status = Orders::PENDING_FAIL;
                $order->remark = $data['approve_remark'];
                if (!$order->save()) {
                    throw new OrderException(OrderException::SAVE_ORDER_FAIL);
                }
                $usersBlack                = new UsersBlack();
                $usersBlack->user_id       = $order->user_id;
                $usersBlack->product_id    = $order->product_id;
                $usersBlack->can_loan_time = $data['can_loan_time'];
                $usersBlack->remark        = $data['approve_remark'];
                if (!$usersBlack->save()) {
                    \Yii::error("新增用户黑名单失败,订单号是:{$order_sn},抛出异常");
                    throw new OrderException(OrderException::SAVE_USER_BLACK_FAIL);
                }
                $params['template_id'] = OrderNoticeService::REVIEW_FAIL;
                // 给用户发送审核失败的通知
                $service = \Yii::$container->get("OrderStatusNoticeService");
                \Yii::$app->redis->Lpush($service->getOrderStatusNoticeKey(), json_encode($params));
            } else {
                throw new OrderException(OrderException::APPROVE_STATUS_FAIL);
            }
        } catch (\Exception $e) {
            throw new OrderException($e->getCode(), $e->getMessage());
        }
    }


    /**
     * 文件描述
     * 文件描述 订单还款结果回调
     * Created On 2019-03-16 10:31
     * Created By heyafei
     * @param $args
     * @throws BaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     * @throws \common\exceptions\ProductException
     * @throws \common\exceptions\RepayPlanFeedbackException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public static function repayStatusFeedback($args)
    {
        try {
            if (empty($args['order_sn'])) {
                throw new OrderException(OrderException::ORDER_NOT_EXIT);
            }
            $orders               = Orders::findOne(['order_sn' => $args["order_sn"]]);
            $user                 = LoanUsers::findOne($orders->user_id);
            $product              = Product::findOne($orders->product_id);
            $data['product_name'] = empty($product->show_name) ? $product->name : $product->show_name;
            $data['phone']        = $user->user_phone;
            $data['user_name']    = $user->real_name;
            $data['user_id']      = $user->id;

            $item_list = CommonMethodsService::orderDueTime([$args["order_sn"]]);
            if (empty($item_list)) {
                throw new OrderException(OrderException::ORDER_NOT_EXIT);
            }
            $plan_item_info = current($item_list);
            $data['amount'] = ($plan_item_info['total_amount'] - $plan_item_info['already_paid']) / 100;

            if ($args['repay_result'] == self::$success) {
                // 拉取还款计划
                $params = [
                    "order_sn" => $args["order_sn"]
                ];
                $res    = CommonMethodsService::openApiCurl($orders->product_id, "getRepayPlan", "POST", $params);
                // 更新还款计划
                RepayPlanFeedbackService::repayPlanFeedback($res['response']);

                // 判断是否已经全部还款
                $res = CommonMethodsService::orderDueTime([$args["order_sn"]]);
                if (empty($res)) {
                    $orders->status = Orders::FINISH;
                }

                // 还款成功通知
                $data['template_id'] = OrderNoticeService::REPAY_SUCCESS;
            } else {
                // 还款失败通知
                $data['template_id'] = OrderNoticeService::REPAY_FAIL;
                $orders->remark      = $args["fail_reason"];
            }
            $orders->save();

            $service = \Yii::$container->get("OrderStatusNoticeService");
            /**
             * @var OrderStatusNoticeService $service
             */
            \Yii::$app->redis->Lpush($service->getOrderStatusNoticeKey(), json_encode($data));
        } catch (BaseException $e) {
            \Yii::error($e->getMessage());
            throw new BaseException($e->getCode(), $e->getMessage());
        }
    }


    /**
     * 文件描述 绑卡结果回调（H5绑卡需要）
     * Created On 2019-03-18 21:41
     * Created By heyafei
     * @param $args
     * @throws \Throwable
     */
    public static function bindCardFeedback($args)
    {
        \Yii::$app->db->transaction(function ($db) use ($args) {
            $orders = Orders::findOne(["order_sn" => $args['order_sn']]);
            if (empty($orders)) {
                throw new OrderException(OrderException::ORDER_NOT_EXIT);
            }
            // 用户银行卡表
            $user_bank = UserBank::findOne(["card_number" => $args['card_number']]);
            if (!$user_bank) {
                $bank_icon              = isset(self::$bank_code_set[$args['bank_name']]['bank_icon']) ? self::$bank_code_set[$args['bank_name']]['bank_icon'] : "";
                $user_bank              = new UserBank();
                $user_bank->user_id     = $orders->user_id;
                $user_bank->bank_name   = $args['bank_name'];
                $user_bank->bank_code   = $args['bank_code'];
                $user_bank->bank_icon   = $bank_icon;
                $user_bank->card_number = $args['card_number'];
                $user_bank->card_phone  = $args['card_phone'];
                $user_bank->card_type   = $args['card_type'];
                if (!$user_bank->save()) {
                    throw new BaseException(BaseException::SAVE_FAIL, json_encode($user_bank->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }

            // 订单表
            if ($orders) {
                if ($args['use_type'] == self::REPAY_CARD) {
                    $orders->repay_bank_id = $user_bank->id;
                } else {
                    $orders->loan_bank_id  = $user_bank->id;
                    $orders->repay_bank_id = $user_bank->id;
                    $orders->status        = Orders::PENDING; // 待审核
                }
                if (!$orders->save()) {
                    throw new BaseException(BaseException::SAVE_FAIL, json_encode($orders->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }

            // 产品银行表
            ProductBank::updateAll(['is_main' => ProductBank::NO_MAIN], ['user_id' => $user_bank->user_id, 'product_id' => $orders->product_id]);
            $product_bank = ProductBank::findOne(['bank_id' => $user_bank->id, 'product_id' => $orders->product_id]);
            if (!$product_bank) {
                $product_bank             = new ProductBank();
                $product_bank->bank_id    = $user_bank->id;
                $product_bank->product_id = $orders->product_id;
            }
            $product_bank->is_main = ProductBank::IS_MAIN;
            if (!$product_bank->save()) {
                throw new BaseException(BaseException::SAVE_FAIL, json_encode($product_bank->getErrors(), JSON_UNESCAPED_UNICODE));
            }

            $user = LoanUsers::findOne($orders->user_id);
            $data = [
                'bankCode'   => $args['bank_code'],
                'bankIcon'   => "",
                'bankName'   => $args['bank_name'],
                'cardNum'    => $args['card_number'],
                'ownerPhone' => $user->user_phone,
                'platUserId' => $user->id,
            ];
            Helper::apiCurl(Helper::getApiUrl("bindCard", "javaApiSecond"), "POST", $data, [], "json");
        });
    }
}