<?php

namespace common\models;

use common\models\mk\MkProductPlatLimit;
use Yii;

/**
 * This is the model class for table "mk_product_plat_limit".
 *
 * @property string $id
 * @property int $app_id app表主键id
 * @property int $product_id mk_product表主键id
 */
class ProductPlatLimit extends MkProductPlatLimit
{

    public function rules()
    {
        return [
            [['app_id', 'product_id'], 'required'],
            [['app_id', 'product_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'product_id' => 'Product ID',
        ];
    }
}
