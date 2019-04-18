<?php

namespace common\models;

use common\exceptions\UserException;
use Lcobucci\JWT\Token;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "loan_users".
 *
 * @property int $id 贷超用户注册信息表ID
 * @property string $user_phone 用户注册手机号码_与merchant_id组合唯一键
 * @property string $user_pwd 用户密码加密
 * @property string $pay_pwd 用户交易密码加密
 * @property int $merchant_id 商户ID_关联loan_merchant表id
 * @property string $card_id 用户身份证号码记录_默认0未认证
 * @property string $real_name 用户真实姓名_默认9未填写
 * @property int $status 用户状态_0正常_1禁用
 * @property string $update_time 修改时间
 * @property string $create_time 创建时间
 * @property string $channel_id
 * @property string $uuid
 */
class LoanUsers extends ActiveRecord
{
    const IS_VALID = 0;
    const NO_VALID = 1;
    const PWD_LOGIN = 1; // 密码登陆
    const MSG_LOGIN = 2; // 短信登陆

    public static $user_id;
    public static $user_info;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%loan_users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_phone'], 'required'],
            [['merchant_id', 'status'], 'integer'],
            [['update_time', 'create_time'], 'safe'],
            [['user_phone', 'channel_id'], 'string', 'max' => 11],
            [['user_pwd', 'pay_pwd'], 'string', 'max' => 32],
            [['card_id'], 'string', 'max' => 18],
            [['real_name'], 'string', 'max' => 100],
            [['user_phone', 'card_id'], 'unique', 'targetAttribute' => ['user_phone', 'card_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_phone' => 'User Phone',
            'user_pwd' => 'User Pwd',
            'pay_pwd' => 'Pay Pwd',
            'merchant_id' => 'Merchant ID',
            'card_id' => 'Card ID',
            'real_name' => 'Real Name',
            'status' => 'Status',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
            'channel_id' => 'Channel ID',
        ];
    }

    public static function findIdentity(Token $jwt) {
        $user_id = $jwt->getClaim('id');
        $user_info = self::find()->where(['status' => self::IS_VALID, 'id' => $user_id])->one();
        if(empty($user_info)) {
            throw new UserException(UserException::INVALID_USER);
        }
        self::$user_info = $user_info;
        self::$user_id = $jwt->getClaim('id');
    }

    public function getId(){
        return self::$user_id;
    }

    public function getIdentity()
    {
        return self::$user_info;
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($insert){
                $this->create_time = date('Y-m-d H:i:s');
                $this->update_time = date('Y-m-d H:i:s');
            } else {
                $this->update_time = date('Y-m-d H:i:s');
            }

            return true;
        } else {
            return false;
        }
    }

    public function getChannels()
    {
        return $this->hasOne(Channels::className(), ['id' => 'channel_id']);
    }

    public function getChannelAssocAccount()
    {
        return $this->hasOne(ChannelAssocAccount::className(), ['channel_id' => 'channel_id'])
            ->where(['channel_assoc_account.account_id' => \Yii::$app->user->identity->id]);
    }
}
