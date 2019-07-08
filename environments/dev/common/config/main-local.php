<?php

use yii\db\Connection;
use yii\mail\MailerInterface;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=todo',
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
                    'useFileTransport' => true,
                    'transport' => [
                        'host' => '',
                        'username' => '',
                        'password' => '',
                        'port' => '',
                        'encryption' => '',
                    ],
                ],
            ],
        ],
    ],
];
