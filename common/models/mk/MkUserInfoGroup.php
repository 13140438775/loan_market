<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_user_info_group".
 *
 * @property int $id 用户信息分组表
 * @property string $group_key group key
 * @property string $group_name 中文名称
 * @property int $pid 父级组
 * @property int $is_hand_term 是否是手填项
 * @property int $type 分组类型0 机构数据分组 1 前端数据分组
 */
class MkUserInfoGroup extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_user_info_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_key', 'group_name', 'is_hand_term', 'type'], 'required'],
            [['pid', 'is_hand_term', 'type'], 'integer'],
            [['group_key', 'group_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户信息分组表',
            'group_key' => 'group key',
            'group_name' => '中文名称',
            'pid' => '父级组',
            'is_hand_term' => '是否是手填项',
            'type' => '分组类型0 机构数据分组 1 前端数据分组',
        ];
    }
}
