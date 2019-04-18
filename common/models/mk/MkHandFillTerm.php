<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_hand_fill_term".
 *
 * @property int $id 手填项表
 * @property string $term_key 手填项key
 * @property string $term_name 名称
 * @property int $type 类型 1 txt 2 单选 3 多选 4地址选择
 * @property string $options 单选和多选的选项 存json {'1':'男','2':'女'}
 * @property int $career_type 手填项类型0不属于任何职业类型 1 上班族 2 企业主 3 个体户 4 自由职业
 * @property int $is_must 是否是必填 0 选填 1 必填
 * @property int $term_group_id 所属分组id
 * @property string $place_holder 输入提示 place_holder
 * @property int $sort 排序 越大越靠前
 * @property int $front_group_id 前端分组id
 * @property int $data_type 数据类型 0 string 1 int
 */
class MkHandFillTerm extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_hand_fill_term';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['term_key', 'term_name', 'career_type', 'term_group_id', 'place_holder', 'front_group_id'], 'required'],
            [['type', 'career_type', 'is_must', 'term_group_id', 'sort', 'front_group_id', 'data_type'], 'integer'],
            [['options'], 'string'],
            [['term_key', 'term_name', 'place_holder'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '手填项表',
            'term_key' => '手填项key',
            'term_name' => '名称',
            'type' => '类型 1 txt 2 单选 3 多选 4地址选择',
            'options' => '单选和多选的选项 存json {\'1\':\'男\',\'2\':\'女\'}',
            'career_type' => '手填项类型0不属于任何职业类型 1 上班族 2 企业主 3 个体户 4 自由职业',
            'is_must' => '是否是必填 0 选填 1 必填',
            'term_group_id' => '所属分组id',
            'place_holder' => '输入提示 place_holder',
            'sort' => '排序 越大越靠前',
            'front_group_id' => '前端分组id',
            'data_type' => '数据类型 0 string 1 int',
        ];
    }
}
