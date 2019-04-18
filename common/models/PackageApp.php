<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "package_app".
 *
 * @property int $id
 * @property string $package_code 标识
 * @property string $name 包名
 * @property string $app_code 所属app标识
 */
class PackageApp extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_code', 'name', 'app_code'], 'required'],
            [['package_code', 'name', 'app_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_code' => '标识',
            'name' => '包名',
            'app_code' => '所属app标识',
        ];
    }
}
