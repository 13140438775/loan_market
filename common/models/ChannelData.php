<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_data".
 *
 * @property int $id id
 * @property string $channel_id 渠道ID
 * @property int $uv_data UV数
 * @property int $pv_data PV数
 * @property int $ip_data IP数
 * @property int $register_data 注册数
 * @property int $login_data 登陆数
 * @property int $date 日期
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ChannelData extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'date'], 'required'],
            [['ip_data', 'pv_data', 'register_data', 'login_data', 'date', 'created_at', 'updated_at'], 'integer'],
            [['channel_id'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => '渠道ID',
            'uv_data' => 'UV',
            'pv_data' => 'PV',
            'ip_data' => 'IP',
            'register_data' => '注册数',
            'login_data' => '登陆数',
            'date' => '日期',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getChannels()
    {
        return $this->hasOne(Channels::className(), ['channel_id' => 'channel_id']);
    }

    public function getChannelAssocAccount()
    {
        return $this->hasOne(ChannelAssocAccount::className(), ['channel_id' => 'channel_id'])
            ->where(['channel_assoc_account.account_id' => Yii::$app->user->identity->id]);
    }

}
