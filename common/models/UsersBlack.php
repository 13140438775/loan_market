<?php
namespace common\models;

use common\models\mk\MkUsersBlack;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * UserBlack 用户黑名单
 */
class UsersBlack extends MkUsersBlack
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
