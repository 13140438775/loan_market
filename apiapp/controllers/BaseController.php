<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : heyafei
 * Created On 2019-01-22 11:24
 */

namespace app\controllers;

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
                'except' => ["h5-register/*", "product/*", "*"]
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
                    '1.3.0' => '!@#$%%^&*(^^)',
                    '1.3.1' => '!@#$%%^&*(^^)'
                ],
                'except' => ["h5-register/*", "product/*", "*"]
            ],
            'tokenValidate'        => [
                'class' => TokenValidate::class,
                'except' => ['login/*', "h5-register/*", 'product/*', "*"]
            ]
        ];
    }
}
