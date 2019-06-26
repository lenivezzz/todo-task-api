<?php
return [
    'auth' => 'auth/index',
    'registration' => 'registration/index',
    'registration/<action:confirm>' => 'registration/<action>',
    'logout/<action:ping>' => 'logout/<action>',
    'profile' => 'profile/index',
];
