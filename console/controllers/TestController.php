<?php

namespace console\controllers;
use common\models\Admin;
use common\models\CreditProduct;
use yii\console\Controller;
class TestController extends Controller
{
    public function actionOrm()
    {
        $product = CreditProduct::findOne(1);
        print_r($product->tagIds);
        return;
    }

}
