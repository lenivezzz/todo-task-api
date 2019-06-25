<?php

use www\extensions\api\TodokeeperInterface;

return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
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
