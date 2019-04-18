<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%product_daily_data}}".
 *
 * @property int $id
 * @property int $product_id credit_product_id
 * @property int $uv UV此渠道每日进中转页的用户数
 * @property int $pv 每日进入中转页的次数
 * @property int $app_id 应用的id apps表的id
 * @property int $date 日期例20190109
 * @property int $created_at
 */
class ProductDailyData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_daily_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'app_id', 'date'], 'required'],
            [['product_id', 'uv', 'pv', 'app_id', 'date', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '产品ID',
            'uv' => 'UV',
            'pv' => 'PV',
            'app_id' => 'App ID',
            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }

    public function getCreditProduct(){
        return $this->hasOne(CreditProduct::class,['id' => 'product_id']);
    }
}
