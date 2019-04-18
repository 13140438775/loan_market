<?php

namespace common\models\mk;

use common\models\Base;
use Yii;

/**
 * This is the model class for table "mk_banner_info".
 *
 * @property int $id
 * @property string $img 图片
 * @property string $title 标题
 * @property string $url 链接
 * @property int $sort 排序
 * @property int $begin_time 生效时间
 * @property int $end_time 失效时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MkBannerInfo extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_banner_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'begin_time', 'end_time', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['img'], 'string', 'max' => 256],
            [['url'], 'string', 'max' => 3000],
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
            'img' => 'Img',
            'url' => 'Url',
            'sort' => 'Sort',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
