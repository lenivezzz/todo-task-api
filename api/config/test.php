<?php

use api\events\handlers\UserConfirmedHandler;
use api\extensions\auth\events\handlers\UserRegisteredHandlerFileLog;
use api\extensions\auth\events\UserConfirmed;
use api\extensions\auth\events\UserRegistered;
use common\components\EventDispatcher;

return [
    'id' => 'app-api-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
        'eventDispatcher' => function () {
            $dispatcher = new EventDispatcher();
            $dispatcher->on(
                UserRegistered::class,
                [
                    Yii::$container->get(UserRegisteredHandlerFileLog::class, [], ['filePath' =>'@runtime/event']),
                    'onUserRegistered'
                ]
            );
            $dispatcher->on(
                UserConfirmed::class,
                [Yii::$container->get(UserConfirmedHandler::class), 'onUserConfirmed']
            );
            return $dispatcher;
        },
    ],
];
