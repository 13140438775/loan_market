<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : huangweihong
 * Created On 2019-03-05 11:24
 */

namespace app\controllers;

use app\services\UserInfoService;
use yii\web\UploadedFile;
use app\models\UpLoad;
use common\exceptions\UserAcceptException;

/**
 * Class BaseController
 * @package api\controllers
 */
class UserInfoController extends BaseController
{
    /**
     * front:用户身份证正面图片
     * back:身份证件反面图片地址
     * face:活体识别头像URL和验证时间
     * picture:手持身份证头像URL和验证时间
     */
    private $uploadType = [
        'front' => 'ocrIdCard',
        'back' => 'ocrIdCard',
        'face' => 'faceid',
        'picture' => 'faceid',
    ];
    //用户信息映射
    private $userInfoType = [
        'basic' => 'getUserBasic',
        'orc' => 'getUserOrc',
        'contact' => 'getUserContact',
        'other' => 'getOther',
    ];

    public function actionIndex(){
    }

    /**
     * 身份认证图片上传
     * @throws UserAcceptException
     */
    public function actionUploadCerit(){
        \Yii::info("uploadCerit接受参数".json_encode(\Yii::$app->request));
        $type = \Yii::$app->request->post('type');
        $delta = \Yii::$app->request->post('delta','');

        if(!isset($this->uploadType[$type])){
            throw new UserAcceptException(UserAcceptException::INVALID_TYPE);
        }
        //多图片上传
        $model = new UpLoad();
        $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
        $file = $model->upload();

        if($file == false){
            throw new UserAcceptException(UserAcceptException::NO_PICTURE);
        }

        $fileUrl = UserInfoService::getInstance()->getOrcInfo($file, $type,$this->uploadType[$type],$delta);
        return ['fileUrl'=>$fileUrl];
    }

    /**
     * 获取运营商验证码
     * @return mixed
     */
    public function actionOperatorCaptcha(){
        $password = \Yii::$app->request->post('password');

        return UserInfoService::getInstance()->getOperatorCaptcha($password);
    }

    /**
     * 运营商认证
     * @return mixed
     */
    public function actionOperatorVerify(){
        $password = \Yii::$app->request->post('password');
        $account = \Yii::$app->request->post('account');
        $captcha = \Yii::$app->request->post('captcha');
        $token = \Yii::$app->request->post('token');
        $website = \Yii::$app->request->post('website');

        return UserInfoService::getInstance()->getOperatorVerify($password,$account,$captcha,$token,$website);
    }

    /**
     * 添加联系人
     * @return mixed
     * @throws UserAcceptException
     */
    public function actionUserContact(){
        $name = \Yii::$app->request->post('name');
        $mobile = \Yii::$app->request->post('mobile');
        $relation = \Yii::$app->request->post('relation');
        $nameSpare = \Yii::$app->request->post('nameSpare');
        $mobileSpare = \Yii::$app->request->post('mobileSpare');
        $relationSpare = \Yii::$app->request->post('relationSpare');

        if(strlen($mobile) < 7 ){
            throw new UserAcceptException(UserAcceptException::RIGHT_PHONE);
        }

        $type = 'POST';
        if(\Yii::$app->request->isPut){
            $type = 'PUT';
        }

        return UserInfoService::getInstance()->addContact($name,$mobile,$relation,$nameSpare,$mobileSpare,$relationSpare,$type);
    }

    /**
     * 其他信息录入
     * @return mixed
     */
    public function actionAddInfo(){
        $info = stripslashes(\Yii::$app->request->post('info'));
        return UserInfoService::getInstance()->addInfo($info);
    }

    /**
     * 运营商认证重置密码第一步，获取验证码
     * @return mixed
     */
    public function actionPasswordCaptcha(){
        return UserInfoService::getInstance()->getPasswordCaptcha();
    }

    /**
     * 运营商认证重置密码
     * @return mixed
     */
    public function actionPasswordRest(){
        $account = \Yii::$app->request->post('account');
        $captcha = \Yii::$app->request->post('captcha');
        $password = \Yii::$app->request->post('password');
        $token = \Yii::$app->request->post('token');
        $website= \Yii::$app->request->post('website');

        return UserInfoService::getInstance()->getPasswordRest($password,$account,$captcha,$token,$website);
    }

    /**
     * 拉取运营商报告
     * @return mixed
     */
    public function actionOperatorReport(){
        ignore_user_abort(true);
        return UserInfoService::getInstance()->getOperatorReport();
    }

    /**
     * 联系人关系枚举
     * @return array
     */
    public function actionContactEnum(){
        $data = [
            'relation' =>['父亲','母亲','儿子','女儿','兄弟','姐妹','配偶'],
            'relationSpare' => ['同学','亲戚','同事','朋友','其他'],
        ];

        return $data;
    }

    /**
     * 获取用户信息
     * @return mixed
     * @throws UserAcceptException
     */
    public function actionLoad(){
        $type = \Yii::$app->request->get('type');
        $productId = \Yii::$app->request->get('product_id');
        if(!isset($this->userInfoType[$type])){
            throw new UserAcceptException(UserAcceptException::INVALID_TYPE);
        }

        $method = $this->userInfoType[$type];

        return UserInfoService::getInstance()->$method($productId);
    }
}
