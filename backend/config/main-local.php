<?php
$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'elTBY2_wOt6KMdHzlkckEnvlES-Ls_5Q',
        ],
    ],
];

if (YII_ENV ==='dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [ // HERE
            'crud' => [
//                'class' => 'yii\gii\generators\crud\Generator',
                'class' => 'backend\gii\crud\CrudGenerator',
                'templates' => [
                    'custom' => '@backend/gii/crud/simple',
                ]
            ]
        ],
    ];

}

return $config;
