<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_bank".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $product_id 产品 ID
 * @property int $bank_id 银行卡ID
 * @property int $is_main 是否是主卡：0-不是主卡 1-主卡
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MkProductBank extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'bank_id'], 'required'],
            [['user_id', 'product_id', 'bank_id', 'is_main', 'created_at', 'updated_at'], 'integer'],
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
            'bank_id' => 'Bank ID',
            'is_main' => 'Is Main',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
