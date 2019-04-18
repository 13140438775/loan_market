<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_assoc_tag".
 *
 * @property string $id
 * @property int $product_id 产品id
 * @property int $tag_id 标签id
 */
class MkProductAssocTag extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_assoc_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'tag_id'], 'required'],
            [['product_id', 'tag_id'], 'integer'],
            [['product_id', 'tag_id'], 'unique', 'targetAttribute' => ['product_id', 'tag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'tag_id' => 'Tag ID',
        ];
    }
}
