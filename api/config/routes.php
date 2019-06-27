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
];
