<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_product_plat_limit".
 *
 * @property string $id
 * @property int $app_id app表主键id
 * @property int $product_id mk_product表主键id
 */
class MkProductPlatLimit extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_product_plat_limit';
    }

    /**
     * {@inheritdoc}
     */
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
