<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_package_versions".
 *
 * @property int $id 包版本表
 * @property string $version_id 版本号
 * @property string $url 下载地址
 * @property int $package_id 所属包id
 * @property int $type 端类型 1安卓 2 IOS企业 3 IOS官方
 * @property int $operator_id 操作人id
 * @property int $created_at 上传时间
 * @property int $updated_at
 */
class MkPackageVersions extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_package_versions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['version_id', 'url', 'package_id', 'type', 'operator_id', 'created_at', 'updated_at'], 'required'],
            [['package_id', 'type', 'operator_id', 'created_at', 'updated_at'], 'integer'],
            [['version_id'], 'string', 'max' => 32],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '包版本表',
            'version_id' => '版本号',
            'url' => '下载地址',
            'package_id' => '所属包id',
            'type' => '端类型 1安卓 2 IOS企业 3 IOS官方',
            'operator_id' => '操作人id',
            'created_at' => '上传时间',
            'updated_at' => 'Updated At',
        ];
    }
}
