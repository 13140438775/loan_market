<?php
/**
 * App params.
 *
 * @Author     heyafei
 * @CreateTime 2019/01/22 15:00:42
 */
return [
    // params validate rules
    'requestHeadersRules' => [
        '*' => [
            [
                [
                    'Authorization',
                    'appMarket',
                    'osVersion',
                    'channelId',
                    'packageName',
                    'appVersion',
                    'deviceType',
                    'deviceId',
                    'sign',
                    'timestamp',
                    'source'
                ],
                'required',
                'message' => '参数非法'
            ]
        ],
    ],
    // params validate rules
    'requestParamsRules' => [
        'credit-product/index' => [
            [
                [
                    'page',
                    'page_num'
                ],
                'required',
                'message' => '参数错误'
            ]
        ],
        'credit-product/hot-product-list' => [
            [
                [
                    'page',
                    'page_num'
                ],
                'required',
                'message' => '参数错误'
            ]
        ],
        'channel-data/stats-channel-data' => [
            [
                'channel_id',
                'required',
                'message' => '参数错误'
            ]
        ],
        'login/captcha-msg' => [
            [
                'phone',
                'required',
                'message' => '参数错误'
            ]
        ],
        'login/register' => [
            [
                [
                    'phone',
                    'verifyCode',
                    'channel_id'
                ],
                'required',
                'message' => '参数错误'
            ]
        ],
        'h5-register/captcha' => [
            [
                'RCaptchaKey',
                'required',
                'message' => '参数错误'
            ]
        ],
    ],
];