<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%product_tag}}".
 *
 * @property int $id tag_id
 * @property string $tag_name 标签名称
 * @property string $tag_icon 首页icon
 * @property string $tag_img 列表中的tag图标
 * @property string $tag url跳转标识
 * @property int $sort 排序
 * @property int $is_enable 是否启用：[0:禁用 1:启用]
 * @property int $is_valid [0:无效	 1:有效]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ProductTag extends \yii\db\ActiveRecord
{
    const IS_ENABLE_ALL = '';
    const IS_ENABLE_NO = 0;
    const IS_ENABLE_YES = 1;
    public static $is_enable_set = [
        self::IS_ENABLE_ALL => '全部',
        self::IS_ENABLE_NO => '禁用',
        self::IS_ENABLE_YES => '启用'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_name', 'tag_icon', 'tag_img'], 'required'],
            [['sort', 'is_enable', 'is_valid', 'created_at', 'updated_at'], 'integer'],
            [['tag_name'], 'string', 'max' => 255],
            [['tag_icon', 'tag_img', 'tag'], 'string', 'max' => 1000],
            ['is_enable','validateEnableChange']
        ];
    }

    public function validateEnableChange($attribute,$params){
        if($this->isNewRecord === false && $this->$attribute == 0 && ProductAssocTag::find()->where(['tag_id'=>$this->id])->count() > 0){
            $this->addError($attribute,'存在关联的展示产品，不能禁用');
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '标签ID',
            'tag_name' => '标签名称',
            'tag_icon' => '首页ICON',
            'tag_img' => '卡片样式',
            'tag' => 'Tag',
            'sort' => '排序',
            'is_enable' => '标签状态',
            'is_valid' => '是否有效',
            'created_at' => '添加时间',
            'updated_at' => '最近更新时间',
        ];
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function getIdMapName(){
        $result = self::find()->select('id, tag_name')->where(['is_valid' => 1,'is_enable' => '1'])->all();
        return ArrayHelper::map($result,'id','tag_name');
    }
}
