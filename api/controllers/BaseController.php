<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : heyafei
 * Created On 2019-01-22 11:24
 */

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use common\exceptions\RequestException;
use common\behaviors\TokenValidate;
use common\behaviors\ParamsValidate;
use common\behaviors\SignValidate;

/**
 * Class BaseController
 * @package api\controllers
 */
class BaseController extends Controller {

    public function behaviors() {
        return [
            'requestHeadersFilter' => [
                'class'   => ParamsValidate::class,
                // 验证数据
                'data'    => Yii::$app->request->getHeaders()->toArray(),
                // 验证规则
                'rules'   => Yii::$app->params['requestHeadersRules'],
                // 错误回调函数
                'errFunc' => function ($data) {
                    throw new ForbiddenHttpException(reset($data), RequestException::INVALID_PARAM);
                },
                'except' => ["credit-product/*", "go/*", "channel-data/*", "h5-register/*", "*"]
            ],
            'requestParamsFilter'  => [
                'class'   => ParamsValidate::class,
                'data'    => array_merge(Yii::$app->request->getQueryParams(), Yii::$app->request->getBodyParams()),
                'rules'   => Yii::$app->params['requestParamsRules'],
                'errFunc' => function ($data) {
                    throw new RequestException(RequestException::INVALID_PARAM, reset($data));
                },
            ],
            'signValidate'         => [
                'class'     => SignValidate::class,
                'secretKey' => [
                    '1.0.0' => '!@#$%%^&*(^^)',
                    '1.1.1' => '!@#$%%^&*(^^)'
                ],
                'except' => ["credit-product/*", "go/*", "channel-data/*", "h5-register/*", "*"]
            ],
            'tokenValidate'        => [
                'class' => TokenValidate::class,
                'except' => ["credit-product/*", "go/*", "channel-data/*", "h5-register/*", "*"]
            ]
        ];
    }
}
