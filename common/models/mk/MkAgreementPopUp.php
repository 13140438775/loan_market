<?php

namespace common\models\mk;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_agreement_pop_up".
 *
 * @property int $id 协议弹出框表ID
 * @property int $user_id 用户ID
 * @property int $app_id apps 主键id
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MkAgreementPopUp extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_agreement_pop_up';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'app_id'], 'required'],
            [['user_id', 'app_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '协议弹出框表ID',
            'user_id' => '用户ID',
            'app_id' => 'apps 主键id',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
