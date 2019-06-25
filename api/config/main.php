<?php

use api\extensions\auth\AuthInterface;
use api\extensions\auth\controllers\LogoutController;
use api\extensions\auth\controllers\RegistrationController;
use api\extensions\auth\events\handlers\UserRegisteredHandler;
use api\extensions\auth\events\UserRegistered;
use api\extensions\auth\Registration;
use api\extensions\auth\RegistrationInterface;
use api\extensions\auth\UsernamePasswordAuthManager;
use api\extensions\auth\controllers\AuthController;
use api\extensions\auth\UserIdentity;
use api\extensions\profile\controllers\ProfileController;
use common\components\EventDispatcher;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use yii\log\FileTarget;
use yii\web\JsonResponseFormatter;
use yii\web\JsonParser;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'controllerMap' => [
        'auth' => AuthController::class,
        'profile' => ProfileController::class,
        'logout' => LogoutController::class,
        'registration' => RegistrationController::class,
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    'class' => JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ]
            ]
        ],
        'user' => [
            'identityClass' => UserIdentity::class,
            'enableAutoLogin' => false,
            'identityCookie' => false,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'eventDispatcher' => function () {
            $dispatcher = new EventDispatcher();
            $dispatcher->on(
                UserRegistered::class,
                [Yii::$container->get(UserRegisteredHandler::class), 'onUserRegistered']
            );
            return $dispatcher;
        },
    ],
    'container' => [
        'definitions' => [
            AuthInterface::class => [
                'class' => UsernamePasswordAuthManager::class,
            ],
            RegistrationInterface::class => [
                'class' => Registration::class,
            ],
        ],
        'singletons' => [
            // Dependency Injection Container singletons configuration
        ],
    ],
    'params' => $params,
];
