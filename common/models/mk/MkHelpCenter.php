<?php

namespace common\models\mk;

use Yii;

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
class MkHelpCenter extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_help_center';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content_type', 'created_at', 'updated_at'], 'integer'],
            [['tip', 'content'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tip' => 'Tip',
            'content' => 'Content',
            'content_type' => 'Content Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
