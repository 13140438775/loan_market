<?php

namespace common\models;

use common\models\mk\MkContactInfo;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * 联系人信息表
 * @package common\models
 */
class ContactInfo extends MkContactInfo
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