<?php

namespace common\models;

use common\models\mk\MkChannelPvLog;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
class ChannelPvLog extends MkChannelPvLog
{
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
}
