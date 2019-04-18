<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "loan_login_log".
 *
 * @property int $id
 * @property int $user_id 客户编号
 * @property string $client_type 客户端类型：Android,Iphone
 * @property string $user_phone
 * @property string $ip_address ip地址
 * @property string $device_id 设备编号
 * @property string $device_name 设备名
 * @property string $app_version app版本
 * @property string $os_version 系统版本
 * @property string $update_time 修改时间
 * @property string $create_time 创建时间
 */
class LoanLoginLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%loan_login_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['update_time', 'create_time'], 'safe'],
            [['client_type', 'ip_address', 'device_id', 'device_name', 'app_version', 'os_version'], 'string', 'max' => 32],
            [['user_phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'client_type' => 'Client Type',
            'user_phone' => 'User Phone',
            'ip_address' => 'Ip Address',
            'device_id' => 'Device ID',
            'device_name' => 'Device Name',
            'app_version' => 'App Version',
            'os_version' => 'Os Version',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
