<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_daily_data}}".
 *
 * @property int $id
 * @property int $app_id app表的主键id
 * @property int $uv 每日进去中转页的用户数去重
 * @property int $date 20190109
 * @property int $pv 每日进去中转页的次数
 * @property int $created_at
 */
class AppDailyData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_daily_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_id', 'date', 'pv', 'created_at'], 'required'],
            [['app_id', 'uv', 'date', 'pv', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'uv' => 'Uv',
            'date' => 'Date',
            'pv' => 'Pv',
            'created_at' => 'Created At',
        ];
    }
}
