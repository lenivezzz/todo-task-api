<?php

use www\extensions\api\FakeTodokeeper;
use www\extensions\api\TodokeeperInterface;

return [
    'id' => 'app-www-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'rules' => [
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
    'container' => [
        'definitions' => [
            TodokeeperInterface::class => [
                'class' => FakeTodokeeper::class,
            ],
        ],
    ],
];
