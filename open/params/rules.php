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
            'user',
            'order',
        ],
        'extraPatterns' => [
            'POST lending-feedback' => 'lending-feedback',
            'POST approve-feedback'  => 'approve-feedback',
            'POST repay-plan-feedback'  => 'repay-plan-feedback',
            'POST repay-status-feedback'  => 'repay-status-feedback',
            'POST bind-card-feedback'  => 'bind-card-feedback',
        ]
    ]
];