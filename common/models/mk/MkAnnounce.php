<?php

namespace common\models\mk;

use common\models\Base;
use Yii;

/**
 * This is the model class for table "mk_announce".
 *
 * @property int $id
 * @property string $content 公告内容
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MkAnnounce extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_announce';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'sort' => 'Sort',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
