<?php

use yii\caching\FileCache;
use yii\mail\MailerInterface;
use yii\swiftmailer\Mailer;

return [
    'name' => 'Todokeeper',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
    ],
    'container' => [
        'definitions' => [
            MailerInterface::class => [
                'class' => Mailer::class,
                'transport' => [
                    'class' => Swift_SmtpTransport::class
                ],
                'viewPath' => '@common/mail',
                'htmlLayout' => '@common/mail/layouts/html',
                'textLayout' => '@common/mail/layouts/text',
            ],
        ],
        'singletons' => [
            // Dependency Injection Container singletons configuration
        ],
    ],
];
