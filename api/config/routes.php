<?php

use yii\rest\UrlRule;

return [
    'auth' => 'auth/index',
    'registration' => 'registration/index',
    'registration/<action:confirm>' => 'registration/<action>',
    'logout/<action:ping>' => 'logout/<action>',
    'profile' => 'profile/index',
    [
        'class' => UrlRule::class,
        'controller' => 'projects',
        'only' => ['index', 'view', 'create', 'update'],
    ],
    'GET projects/<projectId:\d+>/tasks' => 'tasks/index',
    'POST projects/<projectId:\d+>/tasks' => 'tasks/create',
    [
        'class' => UrlRule::class,
        'controller' => 'tasks',
    ],
    [
        'class' => UrlRule::class,
        'controller' => 'tasks',
        'prefix' => 'projects/<projectId:\d+>',
    ],
];
