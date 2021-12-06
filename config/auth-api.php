<?php

return [
    'models' => [
        'user' => null,
        'blocked_user' => null,
        'trusted_device' => null,
    ],
    'account_confirmation' => [
        'max_interval_send_code' => 5,
        'max_fail' => 5
    ],
    'password_reset' => [
        'valid_time' => 30,
    ]
];
