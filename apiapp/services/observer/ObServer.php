<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/8
 * Time: 下午5:23
 */

namespace app\services\observer;

use common\models\Product;

abstract class ObServer
{
    CONST CLOSE = 0;
    CONST OPEN = 1;
    //可见过滤
    abstract protected function _isShow(Product $product);
    //其他过滤
    abstract protected function _filter(Product $product);
}