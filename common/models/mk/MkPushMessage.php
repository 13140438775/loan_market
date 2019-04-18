<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_push_message".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $push_msg 消息
 * @property int $user_id 接收人ID
 * @property int $message_type 消息类型:[1:借款消息 2:系统通知]
 * @property int $is_read 消息类型:[0:未读 1:已读]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MkPushMessage extends \common\models\Base
{
    const NO_READ = 0;
    const IS_READ = 1;

    const LOAN_MESSAGE = 1;
    const SYSTEM_MESSAGE = 2;

    public $message_type_set = [
        self::LOAN_MESSAGE => "借款消息",
        self::SYSTEM_MESSAGE => "系统通知",
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_push_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'message_type', 'is_read', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 256],
            [['push_msg'], 'string', 'max' => 3000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'push_msg' => 'Push Msg',
            'user_id' => 'User ID',
            'message_type' => 'Message Type',
            'is_read' => 'Is Read',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
