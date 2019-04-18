<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%html_manage}}".
 *
 * @property int $id 主键
 * @property string $name 模板名称
 * @property string $url 缩略图地址
 * @property string $param 参数
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class HtmlManage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%html_manage}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name', 'url'], 'required'],
            [['url', 'param'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '模板名称',
            'url' => '缩略图',
            'param' => '参数',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($insert){
                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }

            return true;
        } else {
            return false;
        }
    }
}
