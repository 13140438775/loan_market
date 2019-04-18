<?php

namespace common\models;

use common\models\mk\MkAgreementPopUp;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class AgreementPopUp extends MkAgreementPopUp 
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