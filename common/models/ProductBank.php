<?php

namespace common\models;


use common\models\mk\MkProductBank;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_product_bank".
 *
 * @property int $id
 * @property int $bank_id 银行卡ID
 * @property int $product_id 产品 ID
 * @property int $is_main 是否是主卡：0-不是主卡 1-主卡
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ProductBank extends MkProductBank
{
    const NO_MAIN = 0;
    const IS_MAIN = 1;



    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
