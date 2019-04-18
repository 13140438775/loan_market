<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : heyafei
 * Created On 2019-01-22 11:24
 */

namespace open\controllers;

/**
 * Class BaseController
 * @package api\controllers
 */
class UserController extends BaseController
{

    public function actionIndex()
    {
        return [
            'request' => \Yii::$app->request->getQueryString()
        ];
    }
}
