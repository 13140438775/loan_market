<?php
/**
 * App params.
 *
 * @Author     heyafei
 * @CreateTime 2019/01/22 15:00:42
 */
return [
    // params validate rules
    'requestParamsRules' => [
        '*' => [
            [
                [
                    'ua',
                    'call',
                    // 'args',
                    'sign',
                    'timestamp'
                ],
                'required'
            ]
        ],
        'order/lending-feedback' => [
            [
                [
                    'order_sn', 
                    'lending_status', 
                    'fail_reason',
                    'updated_at'
                ],
                'required',
            ],
            [
                [
                    'order_sn',
                    'fail_reason',
                    'updated_at'
                ],
                'string',
            ],
            [
                [
                    'lending_status',
                ],
                'integer',
            ]
        ],
        'order/approve-feedback' => [
            [
                [
                    'order_sn',
                    'approve_status',
                    'approve_amount',
                    'approve_term',
                    'term_type',
                    'approve_remark',
                    'can_loan_time',
                    'updated_at'
                ],
                'required',
            ],
            [
                [
                    'order_sn',
                    'approve_status',
                    'approve_amount',
                    'approve_term',
                    'term_type',
                    'approve_remark',
                    'can_loan_time',
                    'updated_at'
                ],
                'string',
            ]
        ],
        'order/repay-plan-feedback' => [
            [
                [
                    'order_sn',
                    'total_amount',
                    'total_svc_fee',
                    'received_amount',
                    'already_paid',
                    'total_period',
                    'finish_period',
                    'period_no',
                    'principle',
                    'interest',
                    'service_fee',
                    'bill_status',
                    'total_amount',
                    'already_paid',
                    'loan_time',
                    'due_time',
                    'can_pay_time',
                    'finish_pay_time',
                    'overdue_day',
                    'overdue_fee',
                    'period_fee_desc',
                    'pay_type',
                ],
                'required',
            ],
        ],
        'order/repay-status-feedback' =>[
            [
                [
                    'order_sn',
                    'repay_result',
                    'updated_at'
                ],
                'required',
            ]
        ],
        'order/bind-card-feedback' =>[
            [
                [
                    'bind_status',
                    'bank_code',
                    'bank_name',
                    'user_name',
                    'user_idcard',
                    'card_number',
                    'card_phone',
                    'card_type',
                    'user_phone',
                    'use_type',
                ],
                'required',
            ]
        ],
    ],
];