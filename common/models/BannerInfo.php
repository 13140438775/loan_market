<?php
namespace common\models;

use common\models\mk\MkBannerInfo;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BannerInfo extends MkBannerInfo {


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
        ];
    }

    public function rules()
    {
        return [
            [['sort', 'created_at', 'updated_at'], 'integer'],
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