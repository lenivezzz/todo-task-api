<?php

use www\extensions\api\TodokeeperInterface;

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => '',
        ],
    ],
    'container' => [
        'definitions' => [
            TodokeeperInterface::class => [
                'domain' => '',
            ],
        ],
    ],
];
