<?php
/**
 * RestFul url.
 *
 * @Author     heyafei
 * @CreateTime 2019/01/22 15:00:42
 */
return [
    [
        'class'         => \yii\rest\UrlRule::class,
        'pluralize'     => false,
        'controller'    => [
            'credit-product',
            'channel-data',
            'go',
            'h5-register',
        ],
        'extraPatterns' => [
            "GET hot-product-list" => "hot-product-list",
            "GET tag-list" => "tag-list",
            'GET to' => 'to',
            'GET stats-channel-data' => 'stats-channel-data',
            "GET captcha" => "captcha",
        ]
    ],
    [
        'class'         => \yii\rest\UrlRule::class,
        'pluralize'     => false,
        'controller'    => [
            'login',
        ],
        'extraPatterns' => [
            "GET captcha" => "captcha",
            "POST captcha-msg" => "captcha-msg",
            "GET check-register" => "check-register",
            "POST check-captcha" => "check-captcha",
            "POST login" => "login",
            "POST logout" => "logout",
            "POST register" => "register",
            "POST reset-login-password" => "reset-login-password",
            "POST update-login-password" => "update-login-password",
        ]
    ]
];