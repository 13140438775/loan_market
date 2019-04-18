<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_assoc_account".
 *
 * @property string $id
 * @property int $account_id channel_account id
 * @property int $channel_id channel_id
 * @property int $uv_show uv是否展示
 * @property string $uv_coefficient uv系数
 * @property int $register_show 是否展示注册数
 * @property string $register_coefficient 注册数系数
 * @property int $login_show 注册数是否展示
 * @property string $login_coefficient 登录系数
 * @property int $withdraw_show 是否展示放款数
 * @property string $withdraw_coefficient 提款数系数
 * @property int $field_one_show 备用字段1是否展示
 * @property string $field_one_coefficient 备用字段1系数
 * @property int $field_two_show 备用字段2是否展示
 * @property string $field_two_coefficient 备用字段2系数
 * @property int $field_three_show 备用字段3是否显示
 * @property string $field_three_coefficient 备用字段3系数
 * @property int $field_four_show 备用字段4是否显示
 * @property string $field_four_coefficient 备用字段4系数
 * @property int $field_five_show 备用字段5是否展示
 * @property string $field_five_coefficient 备用字段5系数
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $created_id 创建人id
 * @property int $updated_id 修改人id
 */
class ChannelAssocAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_assoc_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'channel_id', 'created_at', 'updated_at'], 'required'],
            [['account_id', 'channel_id', 'uv_show', 'register_show', 'login_show', 'withdraw_show', 'field_one_show', 'field_two_show', 'field_three_show', 'field_four_show', 'field_five_show', 'created_at', 'updated_at', 'created_id', 'updated_id'], 'integer'],
            [['uv_coefficient', 'register_coefficient', 'login_coefficient', 'withdraw_coefficient', 'field_one_coefficient', 'field_two_coefficient', 'field_three_coefficient', 'field_four_coefficient', 'field_five_coefficient'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'channel_id' => 'Channel ID',
            'uv_show' => 'Uv Show',
            'uv_coefficient' => 'Uv Coefficient',
            'register_show' => 'Register Show',
            'register_coefficient' => 'Register Coefficient',
            'login_show' => 'Login Show',
            'login_coefficient' => 'Login Coefficient',
            'withdraw_show' => 'Withdraw Show',
            'withdraw_coefficient' => 'Withdraw Coefficient',
            'field_one_show' => 'Field One Show',
            'field_one_coefficient' => 'Field One Coefficient',
            'field_two_show' => 'Field Two Show',
            'field_two_coefficient' => 'Field Two Coefficient',
            'field_three_show' => 'Field Three Show',
            'field_three_coefficient' => 'Field Three Coefficient',
            'field_four_show' => 'Field Four Show',
            'field_four_coefficient' => 'Field Four Coefficient',
            'field_five_show' => 'Field Five Show',
            'field_five_coefficient' => 'Field Five Coefficient',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_id' => 'Created ID',
            'updated_id' => 'Updated ID',
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

    public function getchannel(){
        return $this->hasOne(Channels::className(), ['channel_id' => 'channel_id']);
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($insert){
                $this->created_id = Yii::$app->user->id;
                $this->updated_id = Yii::$app->user->id;
                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
                $this->updated_id = Yii::$app->user->id;
            }

            return true;
        } else {
            return false;
        }
    }
}

