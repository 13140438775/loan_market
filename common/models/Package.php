<?php
namespace common\models;

use common\behaviors\OperatorBehavior;
use common\models\mk\MkPackage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Package extends MkPackage {


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
            [
                'class' => OperatorBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_operator_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_operator_id'],
                ],
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_name', 'platform_type'], 'required'],
            [['platform_type'], 'integer'],
            [['package_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_name' => '包名',
            'platform_type' => '所属平台 1 用钱金卡 以后再加2 3 4',
        ];
    }
}