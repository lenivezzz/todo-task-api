<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use www\extensions\api\Todokeeper;
use www\extensions\api\TodokeeperInterface;
use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-www',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'www\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-www',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'registration/confirm/<token>' => 'registration/confirm',
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            TodokeeperInterface::class => [
                'class' => Todokeeper::class,
            ],
            ClientInterface::class => [
                'class' => Client::class,
            ],
        ],
        'singletons' => [
            // Dependency Injection Container singletons configuration
        ],
    ],
    'params' => $params,
];
