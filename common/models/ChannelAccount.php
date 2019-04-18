<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\ChannelAssocAccount;

/**
 * This is the model class for table "channel_account".
 *
 * @property string $id
 * @property string $username 账户
 * @property string $password 密码
 * @property string $salt 盐
 * @property string $email 邮箱
 * @property string $auth_key auth
 * @property string $mobile 手机号
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ChannelAccount extends \yii\db\ActiveRecord implements IdentityInterface
{
    protected $new_salt;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'mobile'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'mobile'], 'string', 'max' => 32],
            [['password', 'email'], 'string', 'max' => 255],
            [['salt','auth_key'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'salt' => 'Salt',
            'email' => 'Email',
            'mobile' => '手机号',
            'created_at' => '创建时间',
            'updated_at' => '上次修改时间',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * 生成salt
     */
    public function doSalt(){
        $this->new_salt = mt_rand(1000, 9999);
        return ;
    }

    public function doPassword($password){
        //新密码以及盐
        $this->doSalt();
        $this->password = $this->password ? $this->password : 'wyxj123';
        $this->salt = $this->new_salt;
        $this->password = md5($this->new_salt.$this->password);
        $this->auth_key = $this->generateAuthKey();

        $this->save();
        return $this->attributes['id'];
    }

    public function beforeSave($insert)
    {
        $this->email = $this->email ? $this->email : '';

        if(parent::beforeSave($insert)){
            if($insert){
//                $this->created_id = Yii::$app->user->id;
//                $this->updated_id = Yii::$app->user->id;
                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
//                $this->updated_id = Yii::$app->user->id;
            }

            return true;
        } else {
            return false;
        }
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return md5($this->salt.$password) === $this->password;
    }

    /**
     * @inheritdoc
     * 根据user_backend表的主键（id）获取用户
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * 根据access_token获取用户，我们暂时先不实现，我们在文章 http://www.manks.top/yii2-restful-api.html 有过实现，如果你感兴趣的话可以先看看
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * 用以标识 Yii::$app->user->id 的返回值
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * 获取auth_key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     * 验证auth_key
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function setPassword($password)
    {
        $this->password = md5($this->salt.$password);
    }


    /**
     * 生成 "remember me" 认证key
     */
    public function generateAuthKey()
    {
       return Yii::$app->security->generateRandomString();
    }

    /*
     * 获取渠道有关信息
     */
    public function getInfo()
    {
        return ChannelAccount::find()
            ->select('
                channel_assoc_account.id AS channel_assoc_account_id,
                channel_account.username,
                channel_account.mobile,
                channels.channel_name,
                channels.id AS channels_id,
                channel_assoc_account.created_at,
                channel_assoc_account.updated_at,
                admin_created.username AS created_name, 
                admin_updated.username AS updated_name, 
                channel_assoc_account.uv_show,
                channel_assoc_account.uv_coefficient,
                channel_assoc_account.register_show,
                channel_assoc_account.register_coefficient,
                channel_assoc_account.login_show,
                channel_assoc_account.login_coefficient,
            ')
            ->leftJoin('channel_assoc_account', 'channel_account.id = channel_assoc_account.account_id')
            ->leftJoin('channels', 'channels.channel_id = channel_assoc_account.channel_id')
            ->leftJoin('admin AS admin_created', 'admin_created.id = channel_assoc_account.created_id')
            ->leftJoin('admin AS admin_updated', 'admin_updated.id = channel_assoc_account.updated_id')
            ->where(['channel_assoc_account.account_id' => Yii::$app->request->get()['id']])
            ->andWhere(['channel_assoc_account.status' => 1])
            ->asArray()
            ->all();
    }

    /*
     * 获取首页信息
     */
    public function getIndexInfo(){
        //获取account_id
        $account_id = \common\models\ChannelAssocAccount::find()
            ->select('account_id')
            ->groupBy('account_id')
            ->asArray()
            ->all();

        //获取每个用户的信息
        if($account_id){
            foreach($account_id as $key => $item){
                $sql = ChannelAccount::find()
                    ->select('
                channel_assoc_account.id AS channel_assoc_account_id,
                channel_assoc_account.account_id,
                channel_account.username,
                channel_account.mobile,
                channels.channel_name,
                channels.id AS channels_id,
                channels.channel_id,
                channel_assoc_account.created_at,
                channel_assoc_account.updated_at,
                channel_account.status,
                admin_created.username AS created_name, 
                admin_updated.username AS updated_name,
            ')
                ->leftJoin('channel_assoc_account', 'channel_account.id = channel_assoc_account.account_id')
                ->leftJoin('channels', 'channels.channel_id = channel_assoc_account.channel_id')
                ->leftJoin('admin AS admin_created', 'admin_created.id = channel_assoc_account.created_id')
                ->leftJoin('admin AS admin_updated', 'admin_updated.id = channel_assoc_account.updated_id')
                ->orderBy('updated_at ASC')
                ->where(['channel_assoc_account.account_id' => $item])
                ->andWhere(['channel_assoc_account.status' => 1])
                ->asArray()
                ->all();

                $info[$key]['channel_name'] = $info[$key]['channel_name'] ?? '';
                $info[$key]['channel_id'] = $info[$key]['channel_id'] ?? '';
                //整合成一条数据
                foreach($sql as $val){
                    $info[$key]['account_id'] = $val['account_id'];
                    $info[$key]['username'] = $val['username'];
                    $info[$key]['mobile'] = $val['mobile'];
                    $info[$key]['created_at'] = $val['created_at'];
                    $info[$key]['updated_at'] = $val['updated_at'];
                    $info[$key]['created_name'] = $val['created_name'];
                    $info[$key]['updated_name'] = $val['updated_name'];
                    $info[$key]['status'] = $val['status'];
                    $info[$key]['channel_name'] .= $val['channel_name'] ? $val['channel_name'].'、' :'';
                    $info[$key]['channel_id'] .= $val['channel_id'] ? $val['channel_id'].'、' :'';
                }
                $info[$key]['channel_name'] = rtrim($info[$key]['channel_name'], "、");
                $info[$key]['channel_id'] = rtrim($info[$key]['channel_id'], "、");

                //删除空值
                if(!isset($info[$key]['account_id'])){
                    unset($info[$key]);
                }

            }
        }
        return $info;
    }

    public function setUVShow($id, $uv_show)
    {
        $model = ChannelAssocAccount::findOne($id);
        $model->uv_show = $uv_show;

        return $model->save();
    }

    public function setUVCoefficient($id, $uv_coefficient)
    {
        $model = ChannelAssocAccount::findOne($id);
        $model->uv_coefficient = $uv_coefficient;

        return $model->save();
    }

    public function setRegisterShow($id, $register_show)
    {
        $model = ChannelAssocAccount::findOne($id);
        $model->register_show = $register_show;

        return $model->save();
    }

    public function setRegisterCoefficient($id, $register_coefficient)
    {
        $model = ChannelAssocAccount::findOne($id);
        $model->register_coefficient = $register_coefficient;

        return $model->save();
    }

    public function setLoginShow($id, $login_show)
    {
        $model = ChannelAssocAccount::findOne($id);
        $model->login_show = $login_show;

        return $model->save();
    }

    public function setLoginCoefficient($id, $login_coefficient)
    {
        $model = ChannelAssocAccount::findOne($id);
        $model->login_coefficient = $login_coefficient;

        return $model->save();
    }

    /*
     * 字符串转换为数组
     */
    public function changeToArray($str){
        foreach(explode(';', $str) as $item){
            if($item){
               $arr[] =  $item;
            }
        }

        return $arr;
    }

    /*
     * 数组转换为字符串
     */
    public function changeToString($arr){
        $str = '';
        foreach($arr as $v){
            $str .= $v;
        }
        return $str;
    }

    /*
     * 判断渠道id唯一性
     */
    public function isUnique($post){
        foreach($post['channel_id'] as $item){
            foreach($this->changeToArray($item) as $value){
                $arr[] = $value;
            }
        }

        if (count($arr) != count(array_unique($arr))) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * 获取channel_name
     */
    public function getChannel($channel_id){
        $channel_arr = '';
        foreach($this->changeToArray($channel_id) as $item){
            $channel_info = Channels::find()->where(['channel_id'=>$item])->asArray()->one();
            if($channel_info){
                $channel_arr .= '<label class="field-label">'.$channel_info['channel_name'].'&nbsp;&nbsp;&nbsp;</label>';
            }
        }

        return $channel_arr;
    }

    /*
     * 创建渠道账户
     */
    public function setChannelAccount($post){
        $this->username = $post['username'];
        $this->mobile = $post['mobile'];
        $account_id = $this->doPassword($post['password']);
        for($i=0; $i<count($post['channel_id']); $i++){
            $arr = $this->changeToArray($post['channel_id'][$i]);
            foreach ($arr AS $val) {
                $data[] = [
                    "account_id" => $account_id,
                    "channel_id" => $val,
                    "uv_show" => $post['uv_show'][$i] ?? 0,
                    "uv_coefficient" => $post['uv_coefficient'][$i] ? $post['uv_coefficient'][$i] : 1,
                    "register_show" => $post['register_show'][$i] ?? 0,
                    "register_coefficient" => $post['register_coefficient'][$i] ? $post['register_coefficient'][$i] : 1,
                    "login_show" => $post['login_show'][$i] ?? 0,
                    "login_coefficient" => $post['login_coefficient'][$i] ? $post['login_coefficient'][$i] : 1,
                    "created_id" => Yii::$app->user->id,
                    "updated_id" => Yii::$app->user->id,
                    "created_at" => time(),
                    "updated_at" => time(),
                ];
            }
        }
        if (empty($data)) {
            return 0;
        }
        $field = array_keys(reset($data));
        return ChannelAssocAccount::find()->createCommand()
            ->batchInsert(ChannelAssocAccount::tableName(), $field, $data)
            ->execute();
    }

    /*
     * 修改渠道账户
     */
    public function changeChannelAccount($post){
        for($i=0; $i<count($post['channel_id']); $i++){
            $arr = $this->changeToArray($post['channel_id'][$i]);
            foreach ($arr AS $val) {
                $data[] = [
                    "account_id" => $post['id'],
                    "channel_id" => $val,
                    "uv_show" => $post['uv_show'][$i] ?? 0,
                    "uv_coefficient" => $post['uv_coefficient'][$i] ? $post['uv_coefficient'][$i] : 1,
                    "register_show" => $post['register_show'][$i] ?? 0,
                    "register_coefficient" => $post['register_coefficient'][$i] ? $post['register_coefficient'][$i] : 1,
                    "login_show" => $post['login_show'][$i] ?? 0,
                    "login_coefficient" => $post['login_coefficient'][$i] ? $post['login_coefficient'][$i] : 1,
                    "created_id" => Yii::$app->user->id,
                    "updated_id" => Yii::$app->user->id,
                    "created_at" => time(),
                    "updated_at" => time(),
                ];
            }
        }
        if (empty($data)) {
            return 0;
        }
        $field = array_keys(reset($data));
        return ChannelAssocAccount::find()->createCommand()
            ->batchInsert(ChannelAssocAccount::tableName(), $field, $data)
            ->execute();
    }

    /*
     * 修改账户状态
     */
    public function setStatus($post){
        $CA_model = ChannelAccount::findOne($post['account_id']);
        if($post){
            switch($post['status']){
                case 0:
                    $CA_model->status = 1;
                    break;

                case 1:
                    $CA_model->status = 0;
                    break;
            }
        }

        $CA_model->save();
    }

    /*
     * 修改渠道状态
     */
    public function setAssocStatus($post){
        $CA_model = \common\models\ChannelAssocAccount::findOne($post['assoc_id']);
        $CA_model->status = 0;

        $CA_model->save();
    }
}







