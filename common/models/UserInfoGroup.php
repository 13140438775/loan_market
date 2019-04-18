<?php

namespace common\models;

use Yii;

/**
 *
 * @property int $id 手填项分组表
 * @property string $group_key group key
 * @property string $group_name 中文名称
 * @property int $pid 父级组
 * @property int $is_hand_term 是否是手填项
 */
class UserInfoGroup extends \common\models\mk\MkUserInfoGroup
{
    const IS_HAND_FILL = 1;
    const IS_NOT_HAND_FILL = 0;
    static $is_hand_fill_set = [
        self::IS_HAND_FILL => '属于手填',
        self::IS_NOT_HAND_FILL => '非手填项'
    ];

    const IS_CAREER_TYPE = 1;
    const IS_NOT_CAREER_TYPE = 0;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_key', 'group_name', 'is_hand_term'], 'required'],
            [['pid', 'is_hand_term'], 'integer'],
            [['group_key', 'group_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '手填项分组表',
            'group_key' => 'group key',
            'group_name' => '中文名称',
            'pid' => '父级组',
            'is_hand_term' => '是否是手填项',
        ];
    }
}
