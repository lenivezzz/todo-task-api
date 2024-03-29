<?php
return [
    [
        'title' => 'Task 1',
        'project_id' => 1,
        'status_id' => 1,
        'expires_at' => (new DateTime('+ 2 days'))->format('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s', '1548675330'),
        'updated_at' => date('Y-m-d H:i:s', '1548675330'),
    ],
    [
        'title' => 'Task 5',
        'project_id' => 1,
        'status_id' => 2,
        'expires_at' => (new DateTime('- 5 days'))->format('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s', '1548675330'),
        'updated_at' => date('Y-m-d H:i:s', '1548675330'),
    ],
    [
        'title' => 'Task 2',
        'project_id' => 5,
        'status_id' => 1,
        'expires_at' => null,
        'created_at' => date('Y-m-d H:i:s', '1548675330'),
        'updated_at' => date('Y-m-d H:i:s', '1548675330'),
    ],
    [
        'title' => 'Task 3',
        'project_id' => 3,
        'status_id' => 1,
        'expires_at' => (new DateTime('+ 1 days'))->format('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s', '1548675330'),
        'updated_at' => date('Y-m-d H:i:s', '1548675330'),
    ],
    [
        'title' => 'Task 4',
        'project_id' => 4,
        'status_id' => 1,
        'expires_at' => (new DateTime('- 1 days'))->format('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s', '1548675330'),
        'updated_at' => date('Y-m-d H:i:s', '1548675330'),
    ],
    [
        'title' => 'NOSEARCHABLETITLE',
        'project_id' => 1,
        'status_id' => 1,
        'expires_at' => null,
        'created_at' => date('Y-m-d H:i:s', '1548675330'),
        'updated_at' => date('Y-m-d H:i:s', '1548675330'),
    ],
];
