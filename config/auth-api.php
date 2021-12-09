<?php

return [
    'models' => [
        'user' => null,
        'password_reset' => null,
        'trusted_device' => null,
    ],
    'route_prefix' => [
        'auth' => '',
        'settings' => ''
    ],
    'route_middleware' => [
        'auth' => [],
        'settings' => []
    ],
    'account_confirmation' => [
        'max_interval_send_code' => 5,
        'max_fail' => 5
    ],
    'password_reset' => [
        'valid_time' => 30,
    ]
];
