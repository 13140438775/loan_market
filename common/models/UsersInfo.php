<?php

namespace common\models;

use Yii;
use common\models\mk\MkUsersInfo;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id 手填项分组表
 * @property string $group_key group key
 * @property string $group_name 中文名称
 * @property int $pid 父级组
 * @property int $is_hand_term 是否是手填项
 */
class UsersInfo extends MkUsersInfo
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
            ],
        ];
    }
}
