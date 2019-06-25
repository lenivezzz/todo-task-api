<?php
return [
    [
        'user_id' => 1,
        'token' => 'token-correct',
        'expires_at' => (new DateTime('+1 day'))->format('Y-m-d H:i:s'),
    ],
    [
        'user_id' => 1,
        'token' => 'token-expired',
        'expires_at' => (new DateTime('last week'))->format('Y-m-d H:i:s'),
    ],
    [
        'user_id' => 2,
        'token' => 'inactive-user-token',
        'expires_at' => (new DateTime('+ 1 week'))->format('Y-m-d H:i:s'),
    ],
];
