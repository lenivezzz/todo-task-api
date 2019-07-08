<?php

use yii\db\Connection;
use yii\mail\MailerInterface;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'redis' => [
            'hostname' => '',
            'port' => '',
        ],
    ],
    'container' => [
        'definitions' => [
            MailerInterface::class => [
                'syncMailer' => [
                    'transport' => [
                        'host' => '',
                        'username' => '',
                        'password' => '',
                        'port' => '587',
                        'encryption' => 'tls',
                    ],
                ],
            ],
        ],
    ],
];
