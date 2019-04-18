<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/8
 * Time: 下午3:52
 */

namespace app\services\observer;

use common\exceptions\ProductException;
use common\models\ContactInfo;
use common\models\HandFillTerm;
use common\models\ProductHandFillConfig;
use common\models\UsersInfo;
use app\services\UserInfoService;
use app\services\SystemJobService;
use yii\helpers\ArrayHelper;

class OrdersObServer
{
    CONST UN_AUTH = 0;
    CONST AUTH = 1;
    CONST EXPIRE_AUTH = 2;
    CONST DAY = 86400;

    CONST ID_NUMBER_Z_PICTURE = 0;
    CONST ID_NUMBER_F_PICTURE = 1;
    CONST FACE_RECONGNITION_PICTURE = 2;
    CONST ID_NUMBER_PICTURE = 3;

    //期限类型映射天数
    private $termType = [
        '1' => 1,
        '2' => 30,
        '3' => 365,
    ];
    private $_user;
    private $_userInfo;

    public function __construct()
    {
        $this->_user = \Yii::$app->user->getIdentity();
        $this->_userInfo = UsersInfo::findOne(['user_id' => $this->_user['id']]);
    }

    /**
     * 判断期限时候在后台定义范围
     * @param $data
     *
     * @return array
     * @throws ProductException
     */
    public function _isBetweenTerm($data){
        $termList = $needRemove = $needAmout = [];

        foreach ($data['termDetail'] as $termDetail){
            array_push($termList,$termDetail['term_time'] * $this->termType[$termDetail['term_time_unit']]);
        }
        foreach ($data['userAccept']['terms'] as $key => $val){
            if(!in_array($val * $this->termType[$data['userAccept']['term_type']],$termList)){
                $needRemove[$key] = $val;
            }else{
                $needAmout[] = $data['termDetail'][$key]['amount'];
            }
        }

        $termTime = array_diff_key($data['userAccept']['terms'], $needRemove);

        if(empty($termTime) || empty($needAmout)){
            \Yii::error("产品{$data['termDetail'][0]['product_id']}可贷期限或金额超过后台配置,详细数据：".json_encode($data));
            throw new ProductException(ProductException::INVALID_VIEW);
        }
        $list['amount'] = $needAmout;
        $list['term_time'] = $termTime;
        $list['term'] = $data['userAccept']['term_type'];

        return $list;
    }

    private $authType = [
        '1' => '_idCardOrc',            //身份证认证
        '2' => '_faceRecognition',      //活体认证
        '3' => '_idNumberPicture',      //手持身份证
        '4' => '_operator',             //运营商
        '5' => '_contactInfo',          //紧急联系人
        '6' => '_device',               //设备信息
        '7' => '_appList',              //applist
        '8' => '_localHistory'          //本地通话记录
    ];

    /**
     * 基础认证列表
     * @param        $authConfig
     * @param bool   $getFlag
     * @param string $productId 兼容单个验证获取资料
     *
     * @return array|mixed
     */
    public function _checkUserBaseInfo($authConfig,$getFlag = false,$productId = ''){
        $data = $orc = [];
        foreach ($authConfig as $val){
            if($this->authType[$val['auth_type']] == '_idCardOrc'){
                $orcSort = $val['sort'];
                $authType = $val['auth_type'];
            }
            $method = $this->authType[$val['auth_type']];
            $info = $this->$method($val);

            //合并数组，保存key名
            if($val['auth_type']<=3){
                $orc = array_merge_recursive($orc,$info);
            }else{
                $data = $data + $info;
            }
            $productId = $val['product_id'];
        }
        //整合身份证认证信息
        if (isset($orc['idcrad_orc'])) {
            if(!$getFlag) {
                $orcInfo = $this->_prepareOrc($orc,$getFlag,$authType,$orcSort);
                array_unshift($data,$orcInfo);
            }else{
                return $orc['idcrad_orc'];
            }
        }

        //手填项认证
        $handFill = $this->_handFill($productId);
        if($handFill && $getFlag){
            return $handFill['data'];
        }

        //999固定为 其他信息认证
        $data[999] = $handFill['add_info'];

        foreach ($data as $key => $row) {
            $volume[$key] = $row['sort'];           //排序
            $edition[$key] = $row['auth_type'];     //类型
        }
        array_multisort($volume, SORT_ASC,$edition,SORT_ASC,  $data);

        return $data;
    }

    /**
     * 聚合身份状态
     * @param $orc
     * @param $getFlag
     * @param $authType
     * @param $orcSort
     *
     * @return array
     */
    private function _prepareOrc($orc,$getFlag,$authType='',$orcSort=''){
        $flag = self::AUTH;

        foreach ($orc['idcrad_orc'] as $key=> $value) {
            if($value['type'] == self::UN_AUTH){
                $flag = self::UN_AUTH;
                continue;
            }
            if($value['type'] == self::EXPIRE_AUTH && $flag != self::UN_AUTH){
                $flag = self::EXPIRE_AUTH;
                continue;
            }
            $orc['idcrad_orc'][$key]['type'] = $flag;
        }

        if(!$getFlag) {
            $orcInfo = [
                'type' => $flag,
                'is_base' => self::AUTH,
                'key' => 'idcrad_orc',
                'name' => '身份证认证',
                'sort' => empty($orcSort)?0:$orcSort,
                'auth_type' => empty($authType)?0:$authType,
            ];

            return $orcInfo;
        }else{
            return $orc['idcrad_orc'];
        }
    }

    /**
     * 身份证无时效性要求，只需判断是否过期
     * 聚合活体和人脸判断
     * @param $config
     *
     * @return array
     */
    public function _idCardOrc($config){
        $data['idcrad_orc'][$config['auth_type']-1]['key'] = UserInfoService::FRONT;
        $data['idcrad_orc'][$config['auth_type']-1]['name'] = '身份证正面';
        $data['idcrad_orc'][$config['auth_type']-1]['is_base'] = $config['is_base'];
        $data['idcrad_orc'][$config['auth_type']-1]['type'] = empty($this->_userInfo['id_number_z_picture'])?self::UN_AUTH:self::AUTH;
        $data['idcrad_orc'][$config['auth_type']-1]['url'] = empty($this->_userInfo['id_number_z_picture'])?'':\Yii::$app->params['oss']['url_prefix'].$this->_userInfo['id_number_z_picture'];
        $data['idcrad_orc'][$config['auth_type']-1]['sort'] = $config['sort'];


        $data['idcrad_orc'][$config['auth_type']]['key'] = UserInfoService::BACK;
        $data['idcrad_orc'][$config['auth_type']]['name'] = '身份证反面';
        $data['idcrad_orc'][$config['auth_type']]['is_base'] = $config['is_base'];
        $data['idcrad_orc'][$config['auth_type']]['type'] = empty($this->_userInfo['id_number_f_picture'])?self::UN_AUTH:self::AUTH;
        $data['idcrad_orc'][$config['auth_type']]['url'] = empty($this->_userInfo['id_number_f_picture'])?'':\Yii::$app->params['oss']['url_prefix'].$this->_userInfo['id_number_f_picture'];
        $data['idcrad_orc'][$config['auth_type']]['sort'] = $config['sort'];

        if( strlen($this->_userInfo['ocr_end_time']) > 5 && time() >strtotime($this->_userInfo['ocr_end_time'])){
            $data['idcrad_orc'][$config['auth_type']-1]['type'] = self::EXPIRE_AUTH;
            $data['idcrad_orc'][$config['auth_type']]['type'] = self::EXPIRE_AUTH;
        }

        return $data;
    }
    /**
     * 活体认证
     * @param $config
     *
     * @return array
     */
    private function _faceRecognition($config){
        $data['idcrad_orc'][$config['auth_type']]['key'] = UserInfoService::FACE;
        $data['idcrad_orc'][$config['auth_type']]['name'] = '活体认证';
        $data['idcrad_orc'][$config['auth_type']]['is_base'] = $config['is_base'];
        $data['idcrad_orc'][$config['auth_type']]['sort'] = $config['sort'];
        $data['idcrad_orc'][$config['auth_type']]['type'] = empty($this->_userInfo['face_recognition_picture'])?self::UN_AUTH:self::AUTH;
        $data['idcrad_orc'][$config['auth_type']]['url'] = empty($this->_userInfo['face_recognition_picture'])?'':\Yii::$app->params['oss']['url_prefix'].$this->_userInfo['face_recognition_picture'];

        if($data['idcrad_orc'][$config['auth_type']]['type']){
            if($config['time_limit'] >= 0
                || (time() - $this->_userInfo['face_recognition_picture_time']) > $config['time_limit'] * self::DAY
            ){
                $data['idcrad_orc'][$config['auth_type']]['type'] = self::EXPIRE_AUTH;
            }
        }
        return $data;
    }

    /**
     * 手持身份证
     * @param $config
     *
     * @return array
     */
    private function _idNumberPicture($config){
        $data['idcrad_orc'][$config['auth_type']]['key'] = UserInfoService::PICTURE;
        $data['idcrad_orc'][$config['auth_type']]['name'] = '手持身份证';
        $data['idcrad_orc'][$config['auth_type']]['sort'] = $config['sort'];
        $data['idcrad_orc'][$config['auth_type']]['type'] = empty($this->_userInfo['id_number_picture'])?self::UN_AUTH:self::AUTH;
        $data['idcrad_orc'][$config['auth_type']]['url'] = empty($this->_userInfo['id_number_picture'])?'':\Yii::$app->params['oss']['url_prefix'].$this->_userInfo['id_number_picture'];

        if($data['idcrad_orc'][$config['auth_type']]['type']){
            if($config['time_limit'] >= 0
                || (time() - $this->_userInfo['id_number_picture_time']) > $config['time_limit'] * self::DAY
            ){
                $data['idcrad_orc'][$config['auth_type']]['type'] = self::EXPIRE_AUTH;
            }
        }
        return $data;
    }

    /**
     * 运营商认证
     * @param $config
     *
     * @return array
     */
    private function _operator($config){
        $data = [
            $config['auth_type'] =>
                ['type' => self::UN_AUTH,'key'=>'operator','name'=>'运营商认证','is_base' => $config['is_base'],'sort' => $config['sort'],'auth_type'=> $config['auth_type']
            ]
        ];

        if($this->_userInfo['operator']){
            $operator = json_decode($this->_userInfo['operator'],true);

            $data[$config['auth_type']]['type'] = self::AUTH;
            if($config['time_limit'] >= 0
                || time() - $operator['time'] > $config['time_limit']*86400
            ){
                $data[$config['auth_type']]['type'] = self::EXPIRE_AUTH;
            }
        }
        return $data;
    }

    /**
     * 联系人认证
     * @param $config
     *
     * @return array
     */
    private function _contactInfo($config){
        $data = [
            $config['auth_type'] => ['type' => self::UN_AUTH,'key'=>'contact','name'=>'联系人认证','is_base' => $config['is_base'],'sort' => $config['sort'],'auth_type'=> $config['auth_type']]
        ];

        if(ContactInfo::findOne(['user_id'=>$this->_user['id']])){
            $data[$config['auth_type']]['type'] = self::AUTH;
        }
        return $data;
    }

    /**
     * 设备信息
     * @param $config
     *
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     * @throws \common\exceptions\SystemJobException
     */
    public function _device($config){

        $device = SystemJobService::getUserInfoList('deviceList');
        $flag = self::AUTH;
        if(empty($device['data'])){
            $flag = self::UN_AUTH;
        }

        $data = [
            $config['auth_type'] =>
                ['type' => $flag,'auth_type'=> $config['auth_type'],'key'=>'deviceList','name'=>'设备信息','is_base' => $config['is_base'],'sort' => $config['sort']]
        ];

        return $data;
    }

    /**
     * appList
     * @param $config
     *
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     * @throws \common\exceptions\SystemJobException
     */
    public function _appList($config){
        $appList = SystemJobService::getUserInfoList('appList');

        $flag = self::AUTH;
        if(empty($appList['data'])){
            $flag = self::UN_AUTH;
        }

        $data = [
            $config['auth_type'] =>
                ['type' => $flag,'auth_type'=> $config['auth_type'],'key'=>'appList','name'=>'appList','is_base' => $config['is_base'],'sort' => $config['sort']]
        ];
        return $data;
    }

    /**
     * 本地通话记录
     * @param $config
     *
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     * @throws \common\exceptions\BaseException
     * @throws \common\exceptions\SystemJobException
     */
    public function _localHistory($config){
        $callHistoryList = SystemJobService::getUserInfoList('callHistoryList');

        $flag = self::AUTH;
        if(empty($callHistoryList['data'])){
            $flag = self::UN_AUTH;
        }

        $data = [
            $config['auth_type'] =>
                ['type' => $flag,'auth_type'=> $config['auth_type'],'key'=>'callHistoryList','name'=>'本地通话记录','is_base' => $config['is_base'],'sort' => $config['sort']]
        ];
        return $data;
    }

    /**
     * 手填项认证
     * @param $productId
     *
     * @return array|bool
     */
    public function _handFill($productId){
        $productHandFillConfig = ProductHandFillConfig::find()->select(['product_id','options','term_id'])->where(['product_id'=>$productId])->asArray()->all();

        if(empty($productHandFillConfig)){
            return false;
        }

        $operator = $this->_userInfo['profession'];
        $operatorArray = json_decode($operator,true);

        $productHandFillConfigByKey = ArrayHelper::map($productHandFillConfig,'term_id','options');
        $termList = array_column($productHandFillConfig,'term_id');
        $handFillTermList = HandFillTerm::find()->select(['id','term_key','term_name','type','options','career_type','front_group_id','place_holder'])->where(['and',['in','id',$termList]])->orderBy('sort')->asArray()->all();

        $flag = self::AUTH;

        foreach ($handFillTermList as $key => $val){
            //匹配筛选项
            $handFillTermList[$key]['use_options'] = '';
            $handFillTermList[$key]['value'] = '';
            if($productHandFillConfigByKey[$val['id']]) {
                $useOptions = array_flip(json_decode($productHandFillConfigByKey[$val['id']], true));
                $options = json_decode($val['options'], true);
                $handFillTermList[$key]['use_options'] = array_intersect_key($options, $useOptions);

            }
            if(empty($handFillTermList[$key]['use_options'])) unset($handFillTermList[$key]['use_options']);
            unset($handFillTermList[$key]['options']);

            if($operatorArray){
                //判断原值，是否在范围内，不在则置空，在则赋值
                if(isset($operatorArray[$val['term_key']]) && $operatorArray[$val['term_key']] && isset($handFillTermList[$key]['use_options'][$operatorArray[$val['term_key']]])){
                    $handFillTermList[$key]['value'] = $operatorArray[$val['term_key']];
                }else{
                    $flag = self::UN_AUTH;
                }
            }else{
                $flag = self::UN_AUTH;
            }
        }
        $data = ['add_info' => ['type' => $flag,'auth_type'=> '999','key'=>'add_info','name'=>'其他信息','is_base' => 0,'sort' => 999],'data'=>$handFillTermList];

        return $data;
    }
}