<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%hot_product}}".
 *
 * @property int $id
 * @property int $product_id credit_product表id
 * @property int $is_enable 是否上架0 否 1 是
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class HotProduct extends \yii\db\ActiveRecord
{
    const ENABLE_YES = 1;
    const ENABLE_NO = 0;
    public static $enable_set = [
        self::ENABLE_NO => '否',
        self::ENABLE_YES => '是'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hot_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'is_enable', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['product_id','sort'],'required'],
            ['product_id','unique','message' => '已存在的热门贷款'],
            ['sort','default','value' => '0']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '产品ID',
            'product_name' => '产品名称',
            'logo' => 'logo',
            'is_enable' => '是否上架',
            'sort' => '排序',
            'created_at' => '添加时间',
            'updated_at' => '最近更新时间',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getProduct(){
        return $this->hasOne(CreditProduct::class,['id'=>'product_id']);
    }

}
