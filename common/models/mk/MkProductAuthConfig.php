<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_auth_config".
 *
 * @property int $id
 * @property int $auth_type 认证项类型1身份证认证 2 活体认证3 手持身份证4运营商5紧急联系人6设备信息7 applist 8本地通话记录
 * @property int $is_need 是否需要1需要 0需要
 * @property int $is_base 是否是基础认证项
 * @property int $sort 排序
 * @property int $data_format 数据格式
 * @property int $time_limit 时效要求 -1 无限 0 每次申请  7天  30天
 * @property int $need_face_score 是否需要人脸分
 * @property int $product_id mk_product表主键id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $last_operator_id 上次操作人id
 */
class MkProductAuthConfig extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_auth_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_type', 'is_need', 'is_base', 'data_format', 'time_limit', 'product_id', 'created_at', 'updated_at', 'last_operator_id'], 'required'],
            [['auth_type', 'is_need', 'is_base', 'sort', 'data_format', 'time_limit', 'need_face_score', 'product_id', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_type' => '认证项类型1身份证认证 2 活体认证3 手持身份证4运营商5紧急联系人6设备信息7 applist 8本地通话记录',
            'is_need' => '是否需要1需要 0需要',
            'is_base' => '是否是基础认证项',
            'sort' => '排序',
            'data_format' => '数据格式',
            'time_limit' => '时效要求 -1 无限 0 每次申请  7天  30天',
            'need_face_score' => '是否需要人脸分',
            'product_id' => 'mk_product表主键id',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_operator_id' => '上次操作人id',
        ];
    }
}
