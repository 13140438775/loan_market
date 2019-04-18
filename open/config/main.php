<?php
$params = array_merge(
    require __DIR__ . '/../params/params.php',
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../environment/' . YII_ENV . '/common/config/params-local.php'
    );
$rules = require __DIR__ . '/../params/rules.php';

return [
    'id' => 'api-open',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'open\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'request' => [
            'class'                => open\components\Request::class,
            'cookieValidationKey'  => 'pPp-f6L-mQeAHtQzBf-jutVfU0Mt2krS',
            //# 取消Cookie验证
            'enableCsrfValidation' => false,
            'parsers'              => [
                'application/json' => \yii\web\JsonParser::class
            ]
        ],
        # 错误处理
        'errorHandler' => [
            'class'            => open\components\ErrorHandle::class,
            'as response'      => [
                'class'    => open\behaviors\ExceptionResponse::class,
            ]
        ],
        'response'     => [
            'format'    => \yii\web\Response::FORMAT_JSON,
            'as filter' => [
                'class'  => open\behaviors\ResponseFilter::class,
                'except' => ["login/captcha", "h5-register/captcha"],
            ]
        ],
        'sms'           => [
            'class' => open\components\Sms::class
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
