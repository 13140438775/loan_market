<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_package".
 *
 * @property int $id
 * @property string $package_name 包名
 * @property int $platform_type 所属平台 1 用钱金卡 以后再加2 3 4
 */
class MkPackage extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_package';
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
