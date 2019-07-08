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
    ],
    'container' => [
        'definitions' => [
            MailerInterface::class => [
                'useFileTransport' => true,
            ],
        ],
    ],
];
