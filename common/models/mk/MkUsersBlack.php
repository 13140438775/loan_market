<?php

namespace common\models\mk;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_users_black".
 *
 * @property int $id 贷超用户注册信息表ID
 * @property int $user_id 用户ID
 * @property int $product_id 产品ID
 * @property string $can_loan_time 可在此申请时间
 * @property string $remark 决绝原因
 * @property string $updated_at 修改时间
 * @property string $created_at 创建时间
 */
class MkUsersBlack extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_users_black';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id','updated_at', 'created_at'], 'integer'],
            [['can_loan_time'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 128],
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
            'can_loan_time' => 'Can Loan Time',
            'remark' => 'Remark',
            'updated_at' => 'Update Time',
            'created_at' => 'Create Time',
        ];
    }
}
