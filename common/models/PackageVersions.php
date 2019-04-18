<?php
namespace common\models;

use common\behaviors\OperatorBehavior;
use common\models\mk\MkPackageVersions;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PackageVersions extends MkPackageVersions
{

    const ANDROID = 1;
    const IOS_COMPANY = 2;
    const IOS = 3;
    static $platform_type_map = [
        self::ANDROID => '安卓',
        self::IOS_COMPANY => 'IOS企业',
        self::IOS => 'IOS官方',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],

            ],
            [
                'class' => OperatorBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_operator_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_operator_id'],
                ],
            ]
        ];
    }
}

