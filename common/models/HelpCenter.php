<?php

namespace common\models;

use common\models\mk\MkHelpCenter;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_help_center".
 *
 * @property int $id
 * @property string $tip 提示
 * @property string $content 内容
 * @property int $content_type 内容类型:[1:借款技巧 2:还款攻略 3:提额妙招 4:其他问题]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class HelpCenter extends MkHelpCenter
{
    const LOAN_SKILL = 1;
    const REPAY_STRATEGY = 2;
    const PROMOTE_LINES_METHODS = 3;
    const OTHER_QUESTIONS = 4;

    public static $content_type_set = [
        self::LOAN_SKILL => "认证相关",
        self::REPAY_STRATEGY => "借款相关",
        self::PROMOTE_LINES_METHODS => "还款相关",
        self::OTHER_QUESTIONS => "其他问题",
    ];

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
