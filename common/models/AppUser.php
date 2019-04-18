<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_user}}".
 *
 * @property int $id
 * @property int $user_id 用户id 不同app可能会相同
 * @property int $app_id 所属app的id
 * @property int $created_at 创建时间
 */
class AppUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'app_id', 'created_at'], 'required'],
            [['user_id', 'app_id', 'created_at'], 'integer'],
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
            'app_id' => 'App ID',
            'created_at' => 'Created At',
        ];
    }
}
