<?php

use yii\caching\FileCache;
use yii\mail\MailerInterface;
use yii\queue\redis\Queue;
use yii\redis\Connection;
use yii\swiftmailer\Mailer;

return [
    'name' => 'Todokeeper',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'mailingqueue',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'redis' => [
            'class' => Connection::class,
        ],
        'mailingqueue' => [
            'class' => Queue::class,
            'redis' => 'redis',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
    ],
    'container' => [
        'definitions' => [
            MailerInterface::class => [
                'class' => \YarCode\Yii2\QueueMailer\Mailer::class,
                'queue' => 'mailingqueue',
                'id' => MailerInterface::class,
                'syncMailer' => [
                    'class' => Mailer::class,
                    'transport' => [
                        'class' => Swift_SmtpTransport::class
                    ],
                    'viewPath' => '@common/mail',
                    'htmlLayout' => '@common/mail/layouts/html',
                    'textLayout' => '@common/mail/layouts/text',
                ],
            ],
        ],
        'singletons' => [
            // Dependency Injection Container singletons configuration
        ],
    ],
];
