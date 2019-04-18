<?php
$params = array_merge(
    require __DIR__ . '/../params/params.php',
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../environment/' . YII_ENV . '/common/config/params-local.php'
    );
$rules = require __DIR__ . '/../params/rules.php';

return [
    'id' => 'api-app',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'Jg'   => [
            'class'     => \common\components\Jg::class,
            'logPath'   => '@logs/jg.log',
            'keyConfig' => [
                'api-app' => [
                    'dev'  => [
                        'appkey'       => '4018a6f072b01500acbb1534',
                        'masterSecret' => '30f23be183f62335ce3c5109',
                    ],
                    'prod' => [
                        'appkey'       => '4018a6f072b01500acbb1534',
                        'masterSecret' => '30f23be183f62335ce3c5109',
                    ]
                ]
            ]
        ],
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
                'except' => ["login/captcha"],
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
            'class' => \common\models\LoanUsers::class
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
                    'levels' => ['error', 'warning','info'],
                    'logVars' => ['_GET','_POST','_FILES']
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
