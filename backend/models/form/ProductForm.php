<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019/2/19
 * Time: 9:49 AM
 */

namespace backend\models\form;

use common\models\CreditProduct;
use yii\base\Model;

class ProductForm extends Model
{
    /** @var CreditProduct */
    private $_creditProduct;
    private $_tagIds;

    public function rules()
    {
        return [
            [['CreditProduct'],'required'],
        ];
    }
}