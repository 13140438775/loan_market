<?php

namespace app\services;

use common\helpers\Helper;
use common\exceptions\UserAcceptException;
use common\exceptions\BaseException;
use common\models\ContactInfo;
use common\models\Product;
use common\models\ProductAuthConfig;
use app\services\observer\OrdersObServer;
use common\models\UserInfoGroup;
use common\models\UsersInfo;

/**
 *
 * Class UserInfoService
 * @package app\services
 */
class UserInfoService
{
    use Base;

    CONST FRONT = 'front';
    CONST BACK = 'back';
    CONST FACE = 'face';
    CONST PICTURE = 'picture';
    CONST PUT = 'PUT';
    private $typePicUrl = [
        'front' => 'id_number_z_picture',
        'back' => 'id_number_f_picture',
        'face' => 'face_recognition_picture',
        'picture' => 'id_number_picture',
    ];

    //活体和人脸类型
    private $normalTime = [
        'face' => 'face_recognition_picture_time',
        'picture' => 'id_number_picture_time',
    ];

    private $typeData = [
        'front' => [
            'ocrName' => 'ocr_name',
            'ocrRace' => 'ocr_race',
            'ocrSex' => 'ocr_sex',
            'ocrBirthday' => 'ocr_birthday',
            'ocrIdNumber' => 'ocr_id_number',
            'ocrAddress' => 'ocr_address',
        ],
        //签发机关、有效期
        'back' => [
            'ocrIssuedBy' => 'ocr_issued_by',
            'ocrStartTime' => 'ocr_start_time',
            'ocrEndTime' => 'ocr_end_time',
        ],
        'face' => ['confidence' => 'face_recognition_picture_score']
    ];
    CONST BASE = 0;
    //职工
    CONST WORKING = 1;
    //企业主
    CONST ENTREPRENEURS = 2;
    //个体户
    CONST SELFEMPLOYED = 3;
    //自由职业
    CONST FREELANCE = 4;
    //学生
    CONST STUDENT = 5;
    //职业类型
    CONST PROFESSION = 'profession_type';

    /**
     * 拉取运营报告
     * @return mixed
     * @throws BaseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function getOperatorReport(){
        try {
            $options = [
                'connect_timeout' => 40,
                'timeout' => 40,
            ];
            $response = Helper::apiCurl(Helper::getApiUrl('report'),'POST',[],$options,'json');

            $operator = [
                'rawUrl' => $response['data']['rawUrl'],
                'reportUrl' => $response['data']['reportUrl'],
                'time' => time(),
                ];
            $data = [
                'operator' => json_encode($operator),
                'operator_online' => Helper::getAge($response['data']['registerTime'],'month'),
            ];

            return UsersInfo::updateAll($data,["user_id"=>\Yii::$app->user->getId()]);
        } catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."拉取运营报告失败, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * TODO 同一用户验证次数限制
     * @param array  $files
     * @param String $type
     * @param String $apiType
     * @param string $delta
     *
     * @return string
     * @throws UserAcceptException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function getOrcInfo(array $files,String $type,String $apiType,$delta = ''){
        $file = $files[0];
        $data = ['image' => $file, 'imageType' => $type];

        if($type == self::FACE){
            $data['idCardNumber'] = \Yii::$app->user->getIdentity()['card_id'];
            $data['idCardName'] = \Yii::$app->user->getIdentity(false)['real_name'];
            $data['image'] = $files;
            $data['delta'] = $delta;
        }

        try {
            if($type == self::PICTURE){
                $response['data'] = [];
            }else{
                $response = Helper::apiCurl(Helper::getApiUrl($apiType),'POST',$data);
            }

            $this->setKeyByType($response['data'],$type,$files);
            return \Yii::$app->params['oss']['url_prefix'].$file;
        } catch (BaseException $e) {
            \Yii::error("图片：{$file}获取ORC信息失败, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 运营商验证码获取
     * @param $password
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     * @throws \Throwable
     */
    public function getOperatorCaptcha($password){
        $data = ['password' => $password];
        try {
            $response = Helper::apiCurl(Helper::getApiUrl('captcha'),'POST',$data,[],'json');

            return $response['data'];
        } catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."运营商密码校验失败, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 运营商认证
     * @param $password
     * @param $account
     * @param $captcha
     * @param $token
     * @param $website
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     * @throws \Throwable
     */
    public function getOperatorVerify($password,$account,$captcha,$token,$website){
        $data = [
            'password' => $password,
            'account' => $account,
            'captcha' => $captcha,
            'token' => $token,
            'website' => $website,
        ];
        try {
            $response = Helper::apiCurl(Helper::getApiUrl('verify'),'POST',$data,[],'json');

            return $response['data'];
        } catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."运营商认证失败, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 更新userInfo 分几种认证
     * @param $data
     * @param $type
     * @param $file
     *
     * @return bool|int
     */
    private function setKeyByType($data,$type,$file){
        $list = array();

        if($type == self::FACE){
            $file = json_encode($file);
        }else{
            $file = $file[0];
        }

        $list[$this->typePicUrl[$type]] = $file;
        foreach ($data as $key => $val) {
            if (isset($this->typeData[$type][$key])) {
                $list[$this->typeData[$type][$key]] = $val;
            }
        }

        if(isset($this->normalTime[$type])) {
            $list[$this->normalTime[$type]] = time();
        }

        $list['user_id'] = \Yii::$app->user->getId();
        $model = new UsersInfo();
        $userInfo = $model::findOne(['user_id'=>$list['user_id']]);

        \Yii::info("用户". $list['user_id'] ."保存用户信息：".json_encode($list));

        if($userInfo){
            $model->setIsNewRecord(false);
            $ret = $model::updateAll($list,['id'=>$userInfo['id']]);
        }else{
            $model->setAttributes($list);
            $ret = $model->save();
        }

        return $ret;
    }

    /**
     * 添加其他联系人
     * @param $name
     * @param $mobile
     * @param $relation
     * @param $nameSpare
     * @param $mobileSpare
     * @param $relationSpare
     * @param string $type
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     * @throws \Throwable
     */
    public function addContact($name,$mobile,$relation,$nameSpare,$mobileSpare,$relationSpare,$type='POST'){

        $data = [
            'name' => (String) $name,
            'mobile' => (String) $mobile,
            'relation' => (String) $relation,
            'nameSpare' => (String) $nameSpare,
            'mobileSpare' => (String) $mobileSpare,
            'relationSpare' => (String) $relationSpare,
        ];
        //大写转换为下划线
        $dataInsert = Helper::uncamelize($data);
        $dataInsert['user_id'] = \Yii::$app->user->getId();

        try {
            Helper::apiCurl(Helper::getApiUrl('contact'),$type,$data,[],'json');

            $model = new ContactInfo();
            if($type == self::PUT){
                $contactInfo = $model::findOne(['user_id'=>\Yii::$app->user->getId()]);
                $model->setIsNewRecord(false);
                $ret = $model::updateAll($dataInsert,['id'=>$contactInfo['id']]);
            }else{
                $model->setAttributes($dataInsert);
                $ret = $model->save();
            }
            return $ret;
        }catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."添加紧急联系人失败, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 其他信息录入
     * @param $info
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \common\exceptions\BaseException
     * @throws \Throwable
     */
    public function addInfo($info){
        $infoArray = json_decode($info,true);
        $data['params'] = $infoArray;

        try {
            $model = new UsersInfo();

            $userId = \Yii::$app->user->getId();
            $userInfo = $model::findOne(['user_id' => $userId]);

            if($userInfo['profession']){
                $profession = json_decode($userInfo['profession'],true);
                $info = json_encode(array_merge($profession,$infoArray),320);
            }

            $res = Helper::apiCurl(Helper::getApiUrl('addInfo'),'POST',$data,[],'json');
            \Yii::info("用户". \Yii::$app->user->getId()."其他信息添加成功, 插入数据: {$res['data']}");
        } catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."其他信息添加失败, error: {$e->getMessage()}");
            throw new BaseException($e->getCode(),$e->getMessage());
        }finally{
            return $model::updateAll(['profession'=>$info],['user_id'=> $userId]);
        }
    }

    /**
     * 重置密码第一步, 获取运营商动态密码
     * @return bool|mixed
     * @throws UserAcceptException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function getPasswordCaptcha(){
        try {
            return Helper::apiCurl(Helper::getApiUrl('passwordCaptcha'),'POST',[],'json');
        }catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."获取动态验证码失败, error: {$e->getMessage()}");
            throw new UserAcceptException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 重置密码第二步, 重置密码
     * @param $password
     * @param $account
     * @param $captcha
     * @param $token
     * @param $website
     *
     * @return bool|mixed
     * @throws UserAcceptException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function getPasswordRest($password,$account,$captcha,$token,$website){
        try {
            $data = [
                'password' => $password,
                'account' => $account,
                'captcha' => $captcha,
                'token' => $token,
                'website' => $website,
            ];
            return Helper::apiCurl(Helper::getApiUrl('passwordReset'),'POST',$data,[],'json');
        }catch (BaseException $e) {
            \Yii::error("用户". \Yii::$app->user->getId()."重置密码失败, error: {$e->getMessage()}");
            throw new UserAcceptException($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 查询姓名，身份证二要素
     * @return array|bool
     * @throws \Throwable
     */
    public function getUserBasic($productId){
        $user = \Yii::$app->user->getIdentity();
        if(!empty($user['card_id']) && !empty($user['real_name'])){
            return [
                'user_name' => $user['real_name'],
                'user_phone' => substr_replace($user['user_phone'], '****', 3, 4),
                'user_idcard' => substr_replace($user['card_id'], '****', -4, 4),
            ];
        }
        return false;
    }

    /**
     * Orc身份认证
     * @param $productId
     *
     * @return array
     * @throws \Throwable
     */
    public function getUserOrc($productId){
        $where = [
            'and',
            ['is_need' => 1],
            ['product_id' => $productId],
            ['in', 'auth_type', [ProductAuthConfig::ID_CARD, ProductAuthConfig::FACE,ProductAuthConfig::PICTURE]],
        ];

        $authConfig = ProductAuthConfig::find()
            ->where($where)
            ->orderBy('is_base desc,sort asc')
            ->asArray()->all();

        $OrdersObServer = new OrdersObServer();
        $user = $OrdersObServer->_checkUserBaseInfo($authConfig,true);
        //处理顺序。。。方便IOS接收
        $data = [];
        foreach ($user as $val){
            $data[] = $val;
        }
        return $data;
    }

    /**
     * 获取用户信息
     * @return array
     */
    public function getUserContact($productId)
    {
        $userId = \Yii::$app->user->getId();
        $contactInfo = ContactInfo::findOne(['user_id' => $userId])->getAttributes();

        return $contactInfo;
    }

    /**
     * 获取手填项（其他信息）
     * @param $productId
     *
     * @return array
     * @throws \Throwable
     */
    public function getOther($productId){
        $OrdersObServer = new OrdersObServer();
        $handFill = $OrdersObServer->_checkUserBaseInfo([],true,$productId);

        $productInfo = Product::findOne(['id' => $productId]);

        $arr = [];
        if($productInfo['is_career_auto']){
            $arr['group_type'] = 1;
            list($data,$default) = $this->getCareerAuto($handFill);
            $arr['career_auto'] = $data;
            $arr['group_info'] = $default;
        }else {
            $arr['group_type'] = 0;
            $data = $this->getFrontGroup($handFill);
            $arr['group_info'] = $data;
        }
        return $arr;
    }

    /**
     * 获取前端分组
     * @param $handFill
     *
     * @return array
     */
    private function getFrontGroup($handFill){
        //获取前端分组
        $redisKey = $this->getRedisKey('frontGroup');
        $userInfoGroupById = $this->getRedisInfo($redisKey);
        if(empty($userInfoGroupById)) {

            $userInfoGroup = UserInfoGroup::find()->where(['type'=>1])->asArray()->all();
            $userInfoGroupById = Helper::mapByKey($userInfoGroup, 'id');

            $this->setRedis($redisKey,json_encode($userInfoGroupById,320));
        }
        $handFillGroup = Helper::groupByKey($handFill, 'front_group_id');

        $data = [];
        $i = 0;

        foreach ($handFillGroup as $key =>$val){
            $data[$i]['group_name'] = $userInfoGroupById[$key]['group_name'];
            $data[$i]['group_info'] = $val;
        }

        return $data;
    }

    /**
     * 职业联动分组
     * @param $handFill
     *
     * @return array
     */
    private function getCareerAuto($handFill){
        $base = $workingGroup = $entrepreneursGroup = $selfEmployedGroup = $freelanceGroup = $studentGroup = [];
        $default = self::WORKING;
        foreach ($handFill as $key => $val){
            if($val['term_key'] == self::PROFESSION && !empty($val['value'])){
                $default = $val['value'];
            }
            switch ($val['career_type']){
                case self::BASE:
                    $base[] = $val;
                    break;
                case self::WORKING:
                    $workingGroup[] = $val;
                    break;
                case self::ENTREPRENEURS:
                    $entrepreneursGroup[] = $val;
                    break;
                case self::SELFEMPLOYED:
                    $selfEmployedGroup[] = $val;
                    break;
                case self::FREELANCE:
                    $freelanceGroup[] = $val;
                    break;
                case self::STUDENT:
                    $studentGroup[] = $val;
                    break;
            }
        }
        $data = [
            self::WORKING => array_merge($base,$workingGroup),
            self::ENTREPRENEURS => array_merge($base,$entrepreneursGroup),
            self::SELFEMPLOYED => array_merge($base,$selfEmployedGroup),
            self::FREELANCE => array_merge($base,$freelanceGroup),
            self::STUDENT => array_merge($base,$studentGroup),
        ];

        foreach ($data as $key=> $val){
            if($val['0']['term_key'] == self::PROFESSION) $val['0']['value'] = $key;
            $data[$key] = $this->getFrontGroup($val);
        }
        return [$data,$data[$default]];
    }
}