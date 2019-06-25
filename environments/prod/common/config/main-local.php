<?php

use yii\db\Connection;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'transport' => [
                'host' => 'smtp.mailgun.org',
                'username' => '',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
            ]
        ],
    ],
];
