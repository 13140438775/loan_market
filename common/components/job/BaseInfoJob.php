<?php
namespace common\components\job;

use common\exceptions\BaseException;
use common\models\LoanUsers;
use common\models\mk\MkUsersInfo;
use common\models\Orders;
use common\services\CommonMethodsService;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;
use common\services\ProductSalesService;

class BaseInfoJob extends BaseObject implements RetryableJobInterface
{
    public $orderSn;
    public $orderInfo;
    public $data;
    private $time = [
        '0' => 5,
        '1' => 5,
        '2' => 10,
        '3' => 30,
        '4' => 600,
        '5' => 1800,
        '6' => 3600,
        '7' => 3600,
        '8' => 3600,
        '9' => 3600,
        '10' => 3600,
    ];

    public function __construct($orderSn){
        $this->orderSn = $orderSn;
        $this->orderInfo = Orders::findOne(['order_sn'=>$orderSn]);
    }

    public function execute($queue)
    {
        try {
            $this->orderInfo = Orders::findOne(['order_sn'=>$this->orderSn]);
            $data = $this->_prepareData();
            \Yii::info("组装数据：".json_encode($data));

            $opt = ['timeout' => 35];
            $response = CommonMethodsService::openApiCurl($this->orderInfo['product_id'], "pushUserBaseInfo", "POST", $data, $opt);

            if($response['status'] == 1 && $response['response']){
                Orders::updateAll(['once_msg'=>'success','once_time'=>1],['order_sn'=>$this->orderSn]);
            }
        }catch (BaseException $e){
            \Yii::error("一推异常：异常码{$e->getCode()},异常信息{$e->getMessage()}");
            $time = $this->orderInfo['once_time']+1;
            \Yii::error("已推送{$time}次");

            if($e->getCode() == BaseException::BASIC_ERROR){
                \Yii::error("status不为500，httpCode为200,不尝试重推，更新推送次数{$time}");
                Orders::updateAll(['once_msg'=>$e->getMessage(),'once_time'=>$time,'status'=>9],['order_sn'=>$this->orderSn]);

                //TODO 是否要还原库存
            }elseif($e->getCode() == BaseException::SYSTEM_ERR || $e->getCode() == BaseException::ERROR_CONFIG){

                $timeStamp = $this->time[$time];
                \Yii::error("已尝试推送{$time}次,下次推送时间为{$timeStamp}秒后");

                if($time<=10) {
                    Orders::updateAll(['once_msg'=>$e->getMessage(),'once_time'=>$time],['order_sn'=>$this->orderSn]);
                    \Yii::$app->push_user_base->delay($timeStamp)->push(new BaseInfoJob($this->orderSn));
                }else{
                    \Yii::error("已推送10次，订单进入审核超时状态");
                    Orders::updateAll(['status'=>9,'once_time'=>10],['order_sn'=>$this->orderSn]);
                    //TODO
                    (new ProductSalesService())->setSalesByPid($this->orderInfo['product_id'],ProductSalesService::FIRST,-1);
                }
            }
        }
        return true;
    }

    //失败重试时间
    public function getTtr()
    {
        return 2 * 60;
    }
    //重试次数
    public function canRetry($attempt, $error)
    {
        return ($attempt < 5) && ($error instanceof TemporaryException);
    }

    /**
     * 数组组装
     * @return mixed
     */
    public function _prepareData(){
        $orderInfo = $this->orderInfo;

        $data['user_info'] = $this->_prepareUser($orderInfo['user_id']);
        $data['order_info'] = $this->_prepareOrder($orderInfo);
        $data['user_verify'] = $this->_prepareOperator($orderInfo['user_id']);

        return $data;
    }

    /**
     * 获取用户信息
     * @param $userId
     *
     * @return array
     */
    public function _prepareUser($userId){
        \Yii::info("查找用户数据：{$userId}");
        $userInfo = LoanUsers::findOne(['id'=>$userId]);
        $user = [
            'user_name' => $userInfo['real_name'],
            'user_phone' => $userInfo['user_phone'],
            'user_idcard' => $userInfo['card_id'],
        ];

        return $user;
    }

    /**
     * 获取订单数据
     * @param $orderInfo
     *
     * @return array
     */
    public function _prepareOrder($orderInfo){
        \Yii::info("调用订单查看数据：{$orderInfo['order_sn']}");
        $order = [
            'order_sn' => $orderInfo['order_sn'],
            'loan_amount' => $orderInfo['loan_amount'],
            'loan_term' => $orderInfo['loan_term'],
            'term_type' => $orderInfo['term_type'],
        ];

        return $order;
    }

    public function _prepareOperator($userId){
        $userInfo = MkUsersInfo::findOne(['user_id'=>$userId]);
        \Yii::info("组装用户信息：{$userId}");
        $operatorList = json_decode($userInfo['operator'],true);
        if(empty($operatorList)){
            return '';
        }
        $operator_verify = file_get_contents($operatorList['rawUrl']);

        $operator['operator_verify']['basic'] = $operator_verify['raw_data']['members']['transactions']['basic'];
        $operator['operator_verify']['calls'] = $operator_verify['raw_data']['members']['transactions']['calls'];
        $operator['operator_verify']['datasource'] = $operator_verify['raw_data']['members']['transactions']['datasource'];
        $operator['operator_verify']['nets'] = $operator_verify['raw_data']['members']['transactions']['nets'];
        $operator['operator_verify']['smses'] = $operator_verify['raw_data']['members']['transactions']['smses'];
        $operator['operator_verify']['transactions'] = $operator_verify['raw_data']['members']['transactions']['transactions'];


        $operator_report_verify = file_get_contents($operatorList['reportUrl']);

        $operator['operator_report_verify']['report'] = [
            'update_time' => date('Y-m-d H:i:s',strtotime($operator_report_verify['report_data']['report']['update_time'])),
        ];
        $operator['operator_report_verify']['application_check'] = $operator_report_verify['report_data']['application_check'];
        $operator['operator_report_verify']['behavior_check'] = $operator_report_verify['report_data']['behavior_check'];
        $operator['operator_report_verify']['cell_behavior'] = $operator_report_verify['report_data']['cell_behavior'];
        $operator['operator_report_verify']['contact_region'] = $operator_report_verify['report_data']['contact_region'];
        $operator['operator_report_verify']['contact_list'] = $operator_report_verify['report_data']['contact_list'];

        $operator['operator_report_verify']['main_service'] = $operator_report_verify['report_data']['main_service'];
        $operator['operator_report_verify']['deliver_address'] = $operator_report_verify['report_data']['deliver_address'];
        $operator['operator_report_verify']['ebusiness_expense'] = $operator_report_verify['report_data']['ebusiness_expense'];
        $operator['operator_report_verify']['trip_info'] = $operator_report_verify['report_data']['trip_info'];

        return $operator;

    }
}