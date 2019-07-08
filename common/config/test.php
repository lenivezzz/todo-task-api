<?php

use yii\mail\MailerInterface;
use yii\swiftmailer\Mailer;

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [

    ],
    'container' => [
        'definitions' => [
            MailerInterface::class => [
                'class' => Mailer::class,
                'useFileTransport' => true,
            ],
        ],
    ]
];
