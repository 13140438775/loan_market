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
                    'authorization',
                    'app-market',
                    'os-version',
                    'channel-id',
                    'package-name',
                    'app-version',
                    'device-type',
                    'device-id',
                    'signature',
                    'request-time',
                    'source'
                ],
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],
    ],
    // params validate rules
    'requestParamsRules' => [
        // 登陆注册
        'login/captcha-msg' => [
            [
                'phone',
                'required',
                'message' => '参数不完整, 请检查'
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
                'message' => '参数不完整, 请检查'
            ]
        ],
        'login/captcha' => [
            [
                'RCaptchaKey',
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],

        // 产品首页/消息
        'product/message-list' => [
            [
                'message_type',
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],
        'product/update-message' => [
            [
                'message_id',
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],
        'product/message-detail' => [
            [
                'message_id',
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],
        'product/product-list' => [
            [
                [
                    'page',
                    'show_type'
                ],
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],
        'product/is-show-loan-product' => [
            [
                "channel_id",
                'required'
            ]
        ],

        'product/loan-calculate' => [
            [
                [
                    'amount',
                    'term',
                    'type',
                    'product_id',
                ],
                'required',
                'message' => '参数不完整, 请检查'
            ]
        ],

        // 银行卡绑定
        'bank/unbind-card-order' => [
            [
                'product_id',
                'required'
            ]
        ],
        'bank/bind-card-page' => [
            [
                'product_id',
                'required'
            ]
        ],
        'bank/user-cards' => [
            [
                'product_id',
                'required'
            ]
        ],
        'bank/get-bank-list' => [
            [
                'product_id',
                'required'
            ]
        ],
        'bank/get-bank-name' => [
            [
                'card_number',
                'required'
            ]
        ],
        'bank/bind-card' => [
            [
                [
                    'product_id',
                    'bank_code',
                    'bank_name',
                    'user_name',
                    'user_idcard',
                    'card_number',
                    'card_phone',
                    'user_phone',
                    'use_type'
                ],
                'required'
            ]
        ],
        'bank/h5-bind-card-callback' => [
            [
                [
                    'order_sn',
                    'bind_status'
                ],
                'required'
            ]
        ],


        // 认证
        'user-info/operator-captcha' => [
            [
                'password',
                'required',
                'message' => '请输入服务商密码'
            ]
        ],
        'user-info/operator-verify' => [
            [
                [
                    'password',
                    'account',
                    'captcha',
                    'token',
                    'website',
                ],
                'required',
                'message' => '请输入验证码'
            ]
        ],
        'user-info/password-rest' => [
            [
                [
                    'password',
                    'account',
                    'captcha',
                    'token',
                    'website',
                ],
                'required',
                'message' => '请输入验证码'
            ]
        ],

        'user-info/user-contact' => [
            [
                [
                    'name',
                    'mobile',
                    'relation',
                    'nameSpare',
                    'mobileSpare',
                    'relationSpare',
                ],
                'required',
                'message' => '参数不完整，请检查'
            ]
        ],
        'user-info/upload-cerit' => [
            [
                [
                    'type',
                ],
                'required',
                'message' => '参数不完整，请检查'
            ]
        ],


        'user-info/add-info' => [
            [
                'info',
                'required',
                'message' => '其他不能为空'
            ]
        ],
        'user-info/load' => [
            [
                ['type', 'product_id'],
                'required'
            ]
        ],

        'system/index' => [
            [
                [
                    'data',
                    'type',
                ],
                'required',
                'message' => '参数不完整，请检查'
            ]
        ],

        // 订单列表/详情
        'orders/order-list' => [
            [
                'status',
                'required',
                'message' => '参数不完整, 请检查'
            ],
            [
                'status,page,page_num',
                'integer'
            ]
        ],  
        'orders/order-detail' => [
            [
                'order_sn',
                'required',
                'message' => '参数不完整, 请检查'
            ],
            [
                'order_sn',
                'string'
            ]
        ],
        'orders/repay-plan-detail' =>[
            [
                ['repay_plan_id','order_id'],
                'required',
                'message' => '参数不完整, 请检查'
            ],
            [
                ['repay_plan_id','order_sn'],
                'integer'
            ]
        ],
        'orders/repay-plan-item-detail' => [
            [
                'repay_plan_item_id',
                'required',
                'message' => '参数不完整, 请检查'
            ],
            [
                'repay_plan_item_id',
                 'integer'
            ]
        ],
        'orders/to-sign-up' => [
            [
                'order_sn',
                'required',
                'message' => '参数不完整, 请检查'
            ],
            [
                'order_sn',
                'string'
            ]
        ],

        'product/push-user-add' => [
            [
                'amount',
                'term',
                'type',
                'product_id',
                'message' => '参数不完整, 请检查'
            ],
            [
                'amount,term,type,product_id',
                'integer'
            ]
        ],


        // 还款
        'repay/repay-orders' => [
            [
                [
                    'repay_plan_id',
                    'repay_pan_item_id'
                ],
                'required'
            ]
        ],
        'repay/repay-page' => [
            [
                [
                    'product_id',
                    'order_sn',
                    'repay_periods',
                    'amount'
                ],
                'required'
            ]
        ],
        'repay/apply-repay' => [
            [
                [
                    'product_id',
                    'order_sn',
                    'repay_periods'
                ],
                'required'
            ]
        ],

        // 登陆注册
        'h5-register/ch5-captcha' => [
            [
                'phone',
                'required'
            ]
        ],
        'h5-register/h5-register' => [
            [
                [
                    'phone',
                    'verifyCode',
                    'channel_id'
                ],
                'required'
            ]
        ],
        'h5-register/channel-pv-log' => [
            [
                "channel_id",
                'required'
            ]
        ],
    ],
];