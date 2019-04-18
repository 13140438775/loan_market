<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : heyafei
 * Created On 2019-01-22 11:24
 */

namespace open\controllers;

use Yii;
use yii\rest\Controller;
use open\exceptions\RequestException;
use open\behaviors\ParamsValidate;
use open\behaviors\SignValidate;

/**
 * Class BaseController
 * @package api\controllers
 */
class BaseController extends Controller {

    public function behaviors() {
        return [
            'requestParamsFilter'  => [
                'class'   => ParamsValidate::class,
                'data'    => array_merge(Yii::$app->request->getQueryParams(), Yii::$app->request->getBodyParams()),
                'rules'   => Yii::$app->params['requestParamsRules'],
                'errFunc' => function ($data) {
                    throw new RequestException(RequestException::INVALID_PARAM, reset($data));
                },
                //'except' => ["*"]
            ],
            'signValidate'         => [
                'class'     => SignValidate::class,
                'secretKey' => [
                    '1.0.0' => '!@#$%%^&*(^^)',
                    '1.1.1' => '!@#$%%^&*(^^)'
                ],
                'except' => ["*"]
            ]
        ];
    }
}
