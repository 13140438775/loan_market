<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/8
 * Time: 下午4:23
 */

namespace app\services;

use app\services\observer\OrdersObServer;
use common\exceptions\BaseException;
use common\exceptions\ProductException;
use common\models\Orders;
use common\models\Product;
use app\services\observer\ProductObServer;
use app\services\observer\UserObServer;
use common\models\ProductProperty;
use common\services\ProductSalesService;
use common\models\ProductTag;
use common\services\CommonMethodsService;
use common\models\ProductTermDetail;
use common\components\job\BaseInfoJob;
use common\models\UsersBlack;
use common\models\ProductAuthConfig;
use common\services\ContractService;


class AcceptService
{
    use Base;
    private $observers;

    CONST BASE = 1;
    CONST NEED = 1;
    private $userAccept = [
        '301' => '用户年龄过大或过小',
        '401' => '用户在机构有未完成的借款',
        '402' => '用户在机构有不良借款记录',
        '403' => '该用户是征信系统黑名单用户',
    ];

    private function __construct(){
        //产品属性监听器
        $this->addObserver(new ProductObserver());
        //添加用户监听器
        $this->addObserver(new UserObServer());
        //添加订单监听器
        $this->addObserver(new OrdersObServer());
    }

    /**
     * 添加监听`
     * @param Observer $observer
     */
    public function addObserver($observer){
        $name = get_class($observer);
        if(!isset($this->observers[$name])){
            $this->observers[$name] = $observer;
        }
    }

    /**
     * 呼叫监听事件
     * @param $method
     * @param $productInfo
     *
     * @return mixed
     */
    private function _callObserver($method, &$productInfo){
        $array = [];
        foreach($this->observers as $observer){
            if(method_exists($observer, $method)){
                $data = $observer->$method($productInfo);
                if(is_array($data)) {
                    $array = $array + $data;
                }
            }
        }
        return $array;
    }

    /**
     * 获取产品合同
     * @param $productId
     *
     * @return mixed
     * @throws BaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOrderContracts($productId){
        $redisKey = $this->getRedisKey($productId);
        $data = $this->getRedisInfo($redisKey);
        if(is_null($data)) {
            $data = ContractService::getOrderContracts($productId);
            $this->setRedis($redisKey,$data);
        }
        return $data;
    }

    /**
     * 产品详情
     * @param $product_id
     *
     * @return array
     * @throws ProductException
     */
    public function getProductDetail($product_id){

        try {
            //加入缓存
            $keyName = $this->getRedisKey($product_id);
            $RedisData = $this->getRedisInfo($keyName);

            $model = new Product();
            if(is_null($RedisData)) {
                $productInfo = $model::findOne(['id' => $product_id]);
                if (empty($productInfo)) {
                    throw new ProductException(ProductException::NOT_FOUND);
                }

                list($base, $property) = $this->_prepareData($productInfo);
                $this->setRedis($keyName,[$base, $property,serialize($productInfo)]);
            }else{
                list($base, $property,$productInfo) = $RedisData;
                $productInfo = unserialize($productInfo);
            }
            //基础验证
            $this->_callObserver('_hasCard', $productInfo);
            //订单验证
            $order = $this->_callObserver('_hasOrder', $productInfo);
            //不存在二推订单则审核
            if(is_null($order)) {
                //产品展示
                $this->_callObserver('_isShow', $productInfo);
                //筛客
                $this->_callObserver('_filter', $productInfo);
            }

            return [$base,$property];
        }catch (ProductException $e){
            $msg = $e->getMessage();

            $message = [
                'product' => empty($base)?'':$base,
                'property' => empty($property)?'':$property
            ];

            if($e->getCode() == ProductException::HAVE_ORDER){
                $message['order_sn'] = $e->getMessage()['order_sn'];
                throw new ProductException($e->getCode(),json_encode($message));
            }elseif($e->getCode() == ProductException::INVALID_USER || $e->getCode() == ProductException::INVALID_VIEW) {
                throw new ProductException($e->getCode(),json_encode($message));
            }else {
                throw new ProductException($e->getCode(), $msg);
            }
        }
    }

    /**
     * 判断金额和期限是否在后台配置区间内
     * @param $userAccept
     * @param $product
     *
     * @return bool
     * @throws ProductException
     */
    public function validateUserAccept($userAccept,$product){
        $where = [
            'and',
            ['product_id' => $product['id']],
            ['>=','amount',$userAccept['min_amount']],
            ['<=','amount',$userAccept['amount']],
        ];

        $productTerm = ProductTermDetail::find()->where($where)->orderBy('amount')->asArray()->all();
        if(empty($productTerm)){
            \Yii::error("产品：{$product['id']}||可贷金额超过后台配置");
            throw new ProductException(ProductException::INVALID_VIEW);
        }

        $data = [
            'userAccept' => $userAccept,
            'termDetail' => $productTerm,
        ];

        $list = $this->_callObserver('_isBetweenTerm', $data);

        $userAccept['accept_terms'] = $list['term_time'];
        $userAccept['accept_amounts'] = $list['amount'];
        $userAccept['accept_term_type'] = $list['term'];

        return $userAccept;
    }

    /**
     * 机构过滤用户
     * @param $product_id
     *
     * @return mixed
     * @throws ProductException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public function isUserAccept($product_id){
        $user = \Yii::$app->user->getIdentity(false);

        $data = [
            'user_name' => $user['real_name'],
            'user_phone' => substr_replace($user['user_phone'], '***', -3, 3),
            'user_idcard' => substr_replace($user['card_id'], '****', -4, 4),
        ];
        $data['md5'] = md5($user['user_phone'].$user['card_id']);
        try {
            //调用第三方接口
            $response = CommonMethodsService::openApiCurl($product_id, "isUserAccept", "POST", $data);

            if($response['response']['result'] == '200'){
                return $response['response'];
            }

            $userBlack = new UsersBlack();
            $black = [
                'user_id' => $user['id'],
                'product_id' => $product_id,
                'can_loan_time' => $response['response']['can_loan_time'],
                'remark' => isset($this->userAccept[$response['response']['result']])?$this->userAccept[$response['response']['result']]:$response['response']['remark'],
            ];

            $userBlack->setAttributes($black);
            $userBlack->save();
        }catch (BaseException $e){
            throw new BaseException($e->getCode(),$e->getMessage());
        }
        throw new ProductException(ProductException::INVALID_USER);
    }

    /**
     * 获取试算接口
     * @param        $amount
     * @param        $term
     * @param        $type
     * @param        $productId
     * @param string $name
     * @param string $phone
     * @param string $idcard
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     */
    public function getLoanCalculate($amount,$term,$type,$productId,$name='',$phone='',$idcard=''){
        //TODO 推送用户之后需要带三要素
        $data = [
            'loan_amount' => $amount,
            'loan_term' => $term,
            'term_type' => $type,
        ];
        if(!empty($name) && !empty($phone) && !empty($idcard)){
            $data['user_name'] = $name;
            $data['user_phone'] = $phone;
            $data['user_idcard'] = $idcard;
        }

        $response = CommonMethodsService::openApiCurl($productId, "loanCalculate", "POST",$data);

        return $response['response'];
    }

    /**
     * 产品数据组装
     * @param $productInfo
     *
     * @return array
     */
    private function _prepareData($productInfo){
        //产品基础资料
        $product = [
            'id' => $productInfo['id'],
            'show_name' => $productInfo['show_name'],
            'logo_url' => \Yii::$app->params['oss']['url_prefix'].$productInfo['logo_url'],
            'show_min_loan_time' => $productInfo['show_min_loan_time'],    //最快放款时间
            'show_interest_desc' => $productInfo['show_interest_desc'],   //息费说明
            'max_amount' => $productInfo['max_amount'],   //最大金额
            'min_amount' => $productInfo['min_amount'],   //最小金额
            'max_term' => $productInfo['max_term'],   //最大期限
            'min_term' => $productInfo['min_term'],   //最小期限
            'term_type' => $productInfo['term_type'],   //期限单位
            'enable_count_limit' => $productInfo['enable_count_limit'],   //基础数量
            'show_try_calc' => $productInfo['show_try_calc'],   //是否隐藏试算字段
        ];
        //产品tagID
        if(!empty($productInfo['show_tag_id'])){
            $productTag = ProductTag::findOne(['id'=>$productInfo['show_tag_id'],'is_enable'=>self::BASE,'is_valid'=>self::BASE]);
            if($productTag) {
                $product['tag_name'] = $productTag['tag_name'];
                $product['tag_icon'] = \Yii::$app->params['oss']['url_prefix'].$productTag['tag_icon'];
                $product['tag_img'] = \Yii::$app->params['oss']['url_prefix'].$productTag['tag_img'];
                $product['tag'] = $productTag['tag'];
            }
        }

        //产品Property
        $productPropertyModel = new ProductProperty();
        $property = $productPropertyModel::findOne(['product_id'=>$productInfo['id']]);
        $productProperty = [];

        if($property){
            $productProperty = [
                'hotline' => $property['hotline'],      //客服电话
                'robot_url' => $property['robot_url'],   //机器人客服地址
                'offline_service' => $property['offline_service'],    //线下客服号
                'is_show_desc_entry' => $property['is_show_desc_entry'],   //产品说明入口是否展示:0是不展示 1 展示
                'show_desc_entry' => [
                    ['name' => '还款方式', 'value' => $property['repay_type'],],
                    ['name' => '提前还款', 'value' => $property['ahead_repay'],],
                    ['name' => '逾期政策', 'value' => $property['overdue_desc'],],
                ],
                'is_show_fee_txt' => $property['is_show_fee_txt'],     //息费说明是否展示0是不展示 1 展示
                'interest_desc' => $property['interest_desc'],       //利率说明
            ];
        }

        return [$product,$productProperty];
    }

    /**
     * 产品认证列
     * @param $product
     * @param $accept
     * 1身份证认证 2 活体认证3 手持身份证4运营商5紧急联系人6设备信息7 applist 8本地通话记录
     * @return array
     */
    public function checkProductAuth($product,$accept){

        $where = ['and' ,
            ['product_id' => $product['id']],
            ['is_need' => self::NEED],
            ['in','auth_type', ProductAuthConfig::$show_auth_type]
        ];

        $authConfig = ProductAuthConfig::find()
            ->where($where)
            ->orderBy('is_base desc,sort asc')
            ->asArray()->all();

        $user = $this->_callObserver('_checkUserBaseInfo', $authConfig);
        $basic = ['0' =>['type' => OrdersObServer::AUTH, 'is_base' => self::BASE,'name'=>'基础认证','key'=>'basic']];
        $data = $basic + $user;

        //检测是否符合一推，待走全流程
        $this->checkBasePush($data,$product,$accept);
        return $data;


    }

    /**
     * 检测是否需要一推
     * @param $data
     * @param $product
     * @param $accept
     *
     * @return bool
     * @throws BaseException
     */
    private function checkBasePush($data,$product,$accept){
        //判断订单状态
        $userId = \Yii::$app->user->getId();
        $order = OrdersService::getInstance()->inProgressOrder($userId,$product['id']);

        if($order){
            return true;
        }
        $type = true;
        foreach ($data as $key => $val){
            if( $val['is_base'] == self::BASE){
                if($val['type'] != OrdersObServer::AUTH){
                    $type = false;
                }
            }
        }
        $productInfo = Product::findOne(['id'=>$product['id']]);
        if($type){
            //是否实名认证
            $this->_callObserver('_hasCard', $productInfo);
            //产品展示
            $this->_callObserver('_isShow', $productInfo);
            //筛客
            $this->_callObserver('_filter', $productInfo);

            $orderSn = OrdersService::getInstance()->createOrder($product['id'],$userId,$accept['amount'],$accept['term'],$accept['term_type']);

            if($orderSn) {
                //统计表sales 更新，获取平台标示
                $ret = (new ProductSalesService())->setSalesByPid($product['id'],ProductSalesService::FIRST);
                if($ret == false){
                    \Yii::error("生成销量数据失败，请检查数据，订单号：".$orderSn);
                    throw new BaseException(BaseException::SYSTEM_ERR);
                }else{
                    \Yii::$app->push_user_base->push(new BaseInfoJob($orderSn));
                }
            }else{
                \Yii::error("生成订单失败，请检查数据");

            }
        }
        return true;
    }

    /**
     * 验证是否符合二推条件，验证项是否符合，并且扣减库存
     * @param $amount
     * @param $term
     * @param $type
     * @param $product_id
     *
     * @return bool
     * @throws BaseException
     * @throws ProductException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function checkPushUserAdd($amount,$term,$type,$product_id){

        \Yii::info("amount：{$amount},term:{$term},termType:{$type},productId:{$product_id}");
        $where = ['and' ,
            ['product_id' => $product_id],
            ['is_need' => 1],
        ];
        $authConfig = ProductAuthConfig::find()
            ->where($where)
            ->orderBy('is_base desc,sort asc')
            ->asArray()->all();

        $checkUser = $this->_callObserver('_checkUserBaseInfo', $authConfig);

        foreach ($checkUser as $val){
            if($val['type'] != OrdersObServer::AUTH){
                throw new ProductException(ProductException::INVALID_PUSH_ADD);
            }
        }

        //验证金额是否在区间内
        $userAccept = $this->isUserAccept($product_id);
        $product = Product::findOne(['id'=>$product_id]);
        $nowAccept = $this->validateUserAccept($userAccept, $product);
        if( !in_array($amount,$nowAccept['accept_amounts']) || !in_array($term,$nowAccept['accept_terms']) || $type !=$nowAccept['accept_term_type']){
            \Yii::error("二推过程中产品可贷期限或金额超过后台配置,详细数据：".json_encode($nowAccept,320));
            throw new ProductException(ProductException::INVALID_VIEW);
        }
        //over

        //二推信息为空
        $orderInfo = OrdersService::getInstance()->inProgressOrder(\Yii::$app->user->getId(),$product_id);
        if(empty($orderInfo['twice_msg']) && $orderInfo['status'] == Orders::UN_FINISH_PUSH){
            $ret = (new ProductSalesService())->setSalesByPid($product['id'],ProductSalesService::SECOND);
            if($ret == false){
                \Yii::error("销量二推数据插入失败，请检查数据，订单号：".$orderInfo['order_sn']);
                throw new BaseException(BaseException::SYSTEM_ERR);
            }else{
                \Yii::$app->push_user_base->push(new BaseInfoJob($orderInfo['order_sn']));}
        }else{
            throw new ProductException(ProductException::EXIST_ORDER);
        }

        return true;
    }

}