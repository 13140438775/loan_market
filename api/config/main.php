<?php
$params = array_merge(
    require __DIR__ . '/../params/params.php',
    require __DIR__ . '/../../environment/' . YII_ENV . '/common/config/params-local.php'
    );
$rules = require __DIR__ . '/../params/rules.php';
require __DIR__ . '/const.php';

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'request' => [
            'class'                => \common\components\Request::class,
            'cookieValidationKey'  => 'pPp-f6L-mQeAHtQzBf-jutVfU0Mt2krS',
            //# 取消Cookie验证
            'enableCsrfValidation' => false,
            'parsers'              => [
                'application/json' => \yii\web\JsonParser::class
            ]
        ],
        # 错误处理
        'errorHandler' => [
            'class'            => \common\components\ErrorHandle::class,
            'as response'      => [
                'class'    => \common\behaviors\ExceptionResponse::class,
            ]
        ],
        'response'     => [
            'format'    => \yii\web\Response::FORMAT_JSON,
            'as filter' => [
                'class'  => \common\behaviors\ResponseFilter::class,
                'except' => ["login/captcha", "h5-register/captcha"],
            ]
        ],
        'sms'           => [
            'class' => \common\components\Sms::class
        ],
        'jwt'          => [
            'class'   => \common\components\Jwt::class,
            'key'     => 'hello_world_pb123456',
            'expTime' => 200000
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        # URL 美化
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules' => $rules,
        ],
    ],
    'params' => $params,
];
