<?php
namespace common\models;

use common\models\mk\MkH5Template;
use common\behaviors\OperatorBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class H5Template extends MkH5Template {

    /************************************* 枚举定义 end **********************************************/
    const IS_NO_SHOW_COMPANY_MAIN_BODY = 0;
    const IS_SHOW_COMPANY_MAIN_BODY = 1;
    static $is_show_company_main_body_set = [
        self::IS_NO_SHOW_COMPANY_MAIN_BODY => '否',
        self::IS_SHOW_COMPANY_MAIN_BODY => '是',
    ];

    const IS_NO_SHOW_RECORD_NUMBER = 0;
    const IS_SHOW_RECORD_NUMBER = 1;
    static $is_show_record_number_set = [
        self::IS_NO_SHOW_RECORD_NUMBER => '否',
        self::IS_SHOW_RECORD_NUMBER => '是',
    ];

    /************************************* 枚举定义 end **********************************************/

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
            [['is_show_company_main_body', 'is_show_record_number', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
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