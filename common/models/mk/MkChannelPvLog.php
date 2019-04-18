<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_channel_pv_log".
 *
 * @property int $id
 * @property int $channel_id 渠道ID
 * @property string $cookie 用户唯一标识
 * @property string $ip 用户IP
 * @property int $date 日期
 * @property int $updated_at 更新时间
 * @property int $created_at 创建时间
 */
class MkChannelPvLog extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_channel_pv_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'date'], 'required'],
            [['channel_id', 'date', 'updated_at', 'created_at'], 'integer'],
            [['cookie', 'ip'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'cookie' => 'Cookie',
            'ip' => 'Ip',
            'date' => 'Date',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
