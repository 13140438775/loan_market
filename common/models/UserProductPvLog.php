<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_product_pv_log".
 *
 * @property int $id 用户点击产品pv日志表
 * @property int $user_id 用户id
 * @property int $product_id 产品id_外键关联credit_product表id
 * @property int $created_date 创建时间
 * @property int $created_time 创建时间
 */
class UserProductPvLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_product_pv_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'created_date', 'created_time'], 'required'],
            [['user_id', 'product_id', 'created_date', 'created_time'], 'integer'],
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
            'product_id' => 'Product ID',
            'created_date' => 'Created Date',
            'created_time' => 'Created Time',
        ];
    }
}
