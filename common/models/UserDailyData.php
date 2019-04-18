<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_daily_data}}".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $product_num 用户每日访问渠道数量
 * @property int $app_id apps表的主键id
 * @property int $date 数据日期20190129
 * @property int $created_at
 */
class UserDailyData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_daily_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_num', 'app_id', 'date', 'created_at'], 'required'],
            [['user_id', 'product_num', 'app_id', 'date', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_num' => 'Product Num',
            'app_id' => 'App ID',
            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }
}
