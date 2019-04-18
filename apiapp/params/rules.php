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
            'login',
            'product',
            'user',
            'system',
            'user-info',
            'orders',
            'agreement-pop-up',
            'test',
            'area'
        ],
        'extraPatterns' => [
            "GET logout" => "logout",
            "GET captcha" => "captcha",
            "POST register" => "register",
            "POST captcha-msg" => "captcha-msg",


            "GET tag-list" => "tag-list",
            "GET banner-list" => "banner-list",
            "GET announce-list" => "announce-list",
            "GET message-type-list" => "message-type-list",
            "GET message-list" => "message-list",
            "GET update-message" => "update-message",
            "GET message-detail" => "message-detail",
            "GET product-list" => "product-list",
            "GET product-index" => "product-index",
            "GET help-center" => "help-center",
            "GET is-show-loan-product" => "is-show-loan-product",


            'GET call-history' => 'call-history',
            'GET operator-captcha' => 'operator-captcha',
            'GET index' => 'index',
            "GET order-index" => "order-index",


            "POST validate-id-card" => "validate-id-card",
            "POST upload-cerit" => 'upload-cerit',
            'POST,PUT user-contact' => 'user-contact',
            "POST operator-captcha" => 'operator-captcha',
            "POST operator-verify" => 'operator-verify',
            "POST password-rest" => 'password-rest',
            "POST add-info" => 'add-info',
            "POST push-user-add" => 'push-user-add',

            "GET password-captcha" => 'password-captcha',
            "GET operator-report" => 'operator-report',
            "GET contact-enum" => 'contact-enum',
            "GET view" => 'view',
            "GET order-contracts" => 'order-contracts',

            "GET order-list" => "order-list",
            "GET order-detail" => "order-detail",
            "GET repay-plan-detail" => "repay-plan-detail",
            "GET repay-plan-item-detail" => "repay-plan-item-detail",
            'GET to-sign-up' => 'to-sign-up',

            "GET get-agreement-pop-up" => "get-agreement-pop-up",
            "POST add-agreement-pop-up" => "add-agreement-pop-up",
            "GET load" => "load",

            'GET black-list' => 'black-list',
            'GET id-card' => 'id-card',

        ]
    ],
    [
        'class'         => \yii\rest\UrlRule::class,
        'pluralize'     => false,
        'controller'    => [
            'bank',
            'repay',
            'h5-register'
        ],
        'extraPatterns' => [
            // 绑卡操作
            "GET unbind-card-order" => "unbind-card-order",
            "GET user-info" => "user-info",
            "GET bind-card-page" => "bind-card-page",
            "GET user-cards" => "user-cards",
            "GET get-bank-list" => "get-bank-list",
            "GET get-bank-name" => "get-bank-name",
            "POST bind-card" => "bind-card",
            "POST h5-bind-card-callback" => "h5-bind-card-callback",

            // 还款操作
            "GET repay-orders" => "repay-orders",
            "POST repay-page" => "repay-page",
            "POST apply-repay" => "apply-repay",

            // H5注册操作
            "POST h5-captcha" => "h5-captcha",
            "POST h5-register" => "h5-register",
            "GET channel-pv-log" => "channel-pv-log",

        ]
    ],
        
];