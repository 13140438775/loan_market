<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/8
 * Time: 下午3:52
 */

namespace app\services\observer;

use app\services\OrdersService;
use common\exceptions\ProductException;
use common\helpers\Helper;
use common\models\UsersInfo;
use common\models\Product;
use common\models\UsersBlack;

class UserObServer extends ObServer
{
    CONST YEAR = 12;
    CONST SIX = 6;
    CONST THREE = 3;
    CONST LESS_THAN_THREE = 3;

    private $_user;
    private $_userInfo;

    private $sourceType = [
        'ios' => 'isIosVisible',
        'android' => 'isAndroidVisible',
    ];

    /**
     * 平台基础认证
     * @throws ProductException
     * @throws \Throwable
     */
    public function _hasCard(){
        if($this->_user = \Yii::$app->user->getIdentity()){
            if(empty($this->_user['card_id']) || empty($this->_user['real_name'])){
                throw new ProductException(ProductException::INVALID_CARD);
            }
        }else{
            throw new ProductException(ProductException::NOT_LOGIN);
        }
    }

    /**
     * 是否存在订单
     * 存在二推订单则需要跳转
     *
     * @param Product $product
     *
     * @return mixed
     * @throws ProductException
     * @throws \Throwable
     */
    public function _hasOrder(Product $product){
        $order = OrdersService::getInstance()->inProgressOrder($this->_user['id'],$product['id']);

        if($order){
            if($order['twice_msg'] == 'success' && $order['twice_time'] >=1) {
                throw new ProductException(ProductException::HAVE_ORDER, $order);
            }
        }
        return $order;
    }

    /**
     * 基于用户基础可见配置
     * @param Product $product
     *
     * @return bool
     * @throws ProductException
     * @throws \Throwable
     */
    public function _isShow(Product $product){

        $this->_userInfo = UsersInfo::findOne(['user_id'=>$this->_user->id]);
        //判断是否在系统小黑屋
        $where = [
            'and',
            ['user_id' => $this->_user->id],
            ['product_id' => $product['id']],
            ['>=','can_loan_time',date('Y-m-d')],
        ];
        if(UsersBlack::find()->where($where)->one()){
            \Yii::error("用户". $this->_user->id."因被拒在小黑屋内");
            throw new ProductException(ProductException::INVALID_USER);
        }

        //获取用户，头部信息SOUCRE
        $source = \Yii::$app->request->getHeaders()->has('source');
        if(!$source){
            \Yii::info("未获取到用户source来源：". $this->_user->id);
            throw new ProductException(ProductException::INVALID_USER);
        }

        //可见端 是否展示，获取用户
        $source = \Yii::$app->request->headers->get('source');
        $sourceT = $this->sourceType[$source];
        if(isset($sourceT) && (!$product->$sourceT())){
            \Yii::info("用户". $this->_user->id."产品{$product['id']}不支持{$source}端显示");
            throw new ProductException(ProductException::INVALID_USER);
        }

        //用户是否可见
        if(!empty($this->_userInfo['operator_online'])){
            if($this->CheckUserVisible($product)){
                \Yii::error("用户". $this->_user->id."身份不符合（首贷，复贷，新客，老客）");
                throw new ProductException(ProductException::INVALID_USER);
            }
        }
        return true;
    }

    /**
     * 客群筛选
     * @param Product $product
     *
     * @return bool
     * @throws ProductException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    public function _filter(Product $product){

        if($product['filter_user_enable'] == self::CLOSE){
            return true;
        }

        //黑名单过滤
        if($product['enable_mobile_black'] == self::OPEN){
            if($this->checkMobileBlack()) {
                \Yii::error("用户". $this->_user->id."被基础平台黑名单过滤");
                throw new ProductException(ProductException::INVALID_USER);
            }
        }

        //地域过滤 身份证前三位或者前6位
        if(!empty($product['area_filter'])){
            if($this->validateIdCard($product['area_filter'],$this->_user->card_id)){
                \Yii::error("用户". $this->_user->id."被地域过滤");
                throw new ProductException(ProductException::INVALID_USER);
            }
        }

        //年龄校验
        if($product['min_age'] > 0 && $product['max_age'] > 0){
            $age = Helper::getAge($this->_userInfo->ocr_birthday);
            if($age < $product['min_age'] || $age > $product['max_age']){
                \Yii::error("用户". $this->_user->id."年龄校验不符合");
                throw new ProductException(ProductException::INVALID_USER);
            }
        }

        //运营商入网时间
        if($this->_userInfo['operator_online'] && !$this->checkNetTime($product)){
            \Yii::error("用户". $this->_user->id."运营商入网时间不符合");
            throw new ProductException(ProductException::INVALID_USER);
        }
    }

    /**
     * 用户是否可见
     * 获取用户两重身份，有过成功放款订单的用户为老客，else为新客||当前产品有成功放款订单的为复贷，else为首贷
     * @param Product $product
     *
     * @return bool true 为不可展示
     */
    private function CheckUserVisible(Product $product){
        //是否新客可见
        if(!$product->isNewUserVisible()){
            //判断是否为新客
            if(OrdersService::getInstance()->isNewUser($this->_user->id)){
                return true;
            }
        }
        //是否老客可见
        if(!$product->isOldUserVisible()){
            //判断是否老客
            if(OrdersService::getInstance()->isOldUser($this->_user->id)){
                return true;
            }
        }
        //是否首贷可见
        if(!$product->isFirstLoanVisible()){
            //判断是否为首贷
            if(OrdersService::getInstance()->isFirstLoan($this->_user->id,$product['id'])){
                return true;
            }
        }
        //是否复贷可见
        if(!$product->isSecondLoanVisible()){
            if(OrdersService::getInstance()->isSecondLoan($this->_user->id,$product['id'])){
                return true;
            }
        }
        return false;
    }

    /**
     * 地域过滤 身份证前三位或者前6位,多位
     * @param $area_filter
     * @param $idCard
     *
     * @return bool true 为不可展示
     */
    private function validateIdCard($area_filter,$idCard){

        $areaArray = explode(',',$area_filter);

        foreach ($areaArray as $area){
            if($area == substr($idCard,0,strlen($area))){
                return true;
            }
        }
        return false;
    }

    /**
     *  >=1年        6个月 至 1年    3个月-至 6个月   小于三个月
     * @param $product
     *
     * @return bool
     */
    private function checkNetTime($product){
        //判断是否在于一年
        $isTrue = false;
        $nowMonth = Helper::getAge($this->_userInfo['operator_online'],'month');

        if($product->isMoreThanOneYear()){
            if( $nowMonth >= self::YEAR){
                $isTrue = true;
            }
        }

        if($product->isBetweenSixAndOneYear()){
            if( $nowMonth >= self::SIX && $nowMonth < self::YEAR){
                $isTrue = true;
            }
        }

        if($product->isBetWeenThreeAndSix()){
            if( $nowMonth >= self::THREE && $nowMonth < self::SIX){
                $isTrue = true;
            }
        }

        if($product->isLessThanThree()){
            if( $nowMonth < self::LESS_THAN_THREE){
                $isTrue = true;
            }
        }
        return $isTrue;
    }

    /**
     * 基础平台黑名单过滤
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     */
    private function checkMobileBlack(){

        $data = ['phone'=> $this->_user['user_phone']];
        try {
            $response = Helper::apiCurl(Helper::getApiUrl('userBlackList'),'POST',$data);
            if($response['data'] == true){
                return true;
            }
        } catch (BaseException $e) {
            \Yii::error("用户". $this->_user->id."被基础平台黑名单过滤, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }
        return false;
    }
}