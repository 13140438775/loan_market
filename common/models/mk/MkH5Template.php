<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_h5_template".
 *
 * @property int $id h5 模板主键id
 * @property string $h5_template_name 模板名称
 * @property string $abbreviation_img 缩略图url
 * @property string $banner_img banner url
 * @property string $background_color 背景色
 * @property string $submit_img 提交按钮 img
 * @property int $is_show_company_main_body 是否展示公司主体 0否 1是
 * @property int $is_show_record_number 是否展示备案号
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_operator_id 最后操作人id
 */
class MkH5Template extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_h5_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_show_company_main_body', 'is_show_record_number', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['h5_template_name'], 'string', 'max' => 50],
            [['abbreviation_img', 'banner_img', 'submit_img'], 'string', 'max' => 200],
            [['background_color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'h5 模板主键id',
            'h5_template_name' => '模板名称',
            'abbreviation_img' => '缩略图url',
            'banner_img' => 'banner url',
            'background_color' => '背景色',
            'submit_img' => '提交按钮 img',
            'is_show_company_main_body' => '是否展示公司主体 0否 1是',
            'is_show_record_number' => '是否展示备案号',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_operator_id' => '最后操作人id',
        ];
    }
}
