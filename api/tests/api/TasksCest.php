<?php
declare(strict_types=1);

namespace api\tests\api;

use api\extensions\auth\fixtures\TokenFixture;
use api\fixtures\ProjectFixture;
use api\fixtures\TaskFixture;
use api\models\task\Task;
use api\tests\ApiTester;
use common\fixtures\UserFixture;

class TasksCest
{
    public function _before(ApiTester $I) : void
    {
        $I->haveFixtures([
            'auth_data' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'auth_data.php',
            ],
            'token' => [
                'class' => TokenFixture::class,
                'dataFile' => codecept_data_dir() . 'token.php',
            ],
            'project' => [
                'class' => ProjectFixture::class,
                'dataFile' => codecept_data_dir() . 'project.php',
            ],
            'task' => [
                'class' => TaskFixture::class,
                'dataFile' => codecept_data_dir() . 'task.php',
            ],
        ]);
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function access(ApiTester $I) : void
    {
        $I->sendGET('/tasks');
        $I->seeResponseCodeIs(401);

        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/tasks');
        $I->seeResponseCodeIs(200);
    }

    public function index(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/tasks');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
                'project_id' => 1,
            ],
            [
                'title' => 'Task 2',
                'project_id' => 5,
            ]
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.._links.self.href');

        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 3',
            'project_id' => 3,
        ]]);

        $I->sendGET('/projects/1/tasks');
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
                'project_id' => 1,
            ]
        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 2',
            'project_id' => 5,
        ]]);
    }

    public function create(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $expiresAt = date('Y-m-d H:i:s', time() + 84400);
        $I->sendPOST('/tasks', [
            'title' => 'Task 123',
            'project_id' => 1,
            'expires_at' => $expiresAt,
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'title' => 'Task 123',
            'project_id' => 1,
            'expires_at' => $expiresAt,
        ]);

        $I->sendPOST('/tasks', [
            'title' => 'Task 222',
            'project_id' => 1,
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'title' => 'Task 222',
            'project_id' => 1,
            'expires_at' => null,
        ]);

        $I->sendPOST('/tasks', [
            'title' => 'Task 222',
            'project_id' => 456,
        ]);
        $I->seeResponseCodeIs(422);

        $I->sendPOST('/projects/1/tasks', [
            'title' => 'Task 555',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'title' => 'Task 555',
            'project_id' => 1,
            'expires_at' => null,
        ]);

        $I->sendPOST('/projects/3/tasks', [
            'title' => 'Task 555',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function view(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/tasks/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'Task 1',
            'project_id' => 1,
            'status_id' => 1,
        ]);
        $I->seeResponseJsonMatchesJsonPath('$_links.self.href');

        $I->sendGET('/projects/1/tasks/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'Task 1',
            'project_id' => 1,
            'status_id' => 1,
        ]);

        $I->sendGET('/projects/25/tasks/2');
        $I->seeResponseCodeIs(403);

        $I->sendGET('/projects/4/tasks/5');
        $I->seeResponseCodeIs(403);

        $I->sendGET('/tasks/5');
        $I->seeResponseCodeIs(403);

        $I->sendGET('/tasks/88');
        $I->seeResponseCodeIs(404);
    }

    public function delete(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/tasks/4');
        $I->seeResponseCodeIs(403);

        $I->sendDELETE('/tasks/1');
        $I->seeResponseCodeIs(204);

        $I->sendDELETE('/projects/1/tasks/2');
        $I->seeResponseCodeIs(204);
    }

    public function update(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/tasks/4');
        $I->seeResponseCodeIs(403);

        $title = 'Updated title' . time();
        $expiresAt = date('Y-m-d H:i:s', time() + 1000);
        $attributes = [
            'title' => 'Updated title' . $title,
            'status_id' => 2,
            'expires_at' => $expiresAt,
        ];
        $I->sendPATCH('/tasks/1', $attributes);
        $I->seeResponseCodeIs(200);
        $I->canSeeRecord(Task::class, $attributes);

        $I->sendPATCH('/tasks/1', [
            'expires_at' => null,
        ]);
        $I->seeResponseCodeIs(200);
        $I->dontSeeRecord(Task::class, $attributes);
    }

    public function search(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/tasks?s[title]=Task');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],
            [
                'title' => 'Task 5',
            ],
            [
                'title' => 'Task 2',
            ],
        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 3',
        ]]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'NOSEARCHABLETITLE',
            'project_id' => 1,
        ]]);

        $I->sendGET('/tasks?s[status_id]=1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],
            [
                'title' => 'Task 2',
            ],
        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 3',
        ]]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 5',
        ]]);

        $I->sendGET('/tasks?s[status_id]=1,2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],
            [
                'title' => 'Task 2',
            ],
            [
                'title' => 'Task 5',
            ],
        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 3',
        ]]);

        $I->sendGET('/projects/1/tasks');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],
            [
                'title' => 'Task 5',
            ],
        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 3',
        ]]);

        $I->sendGET('/projects/3/tasks');
        $I->seeResponseCodeIs(403);

        $I->sendGET('/tasks?s[expires_at_start]=' . date('Y-m-d H:i:s'));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],

        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 2',
        ]]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 5',
        ]]);

        $I->sendGET('/tasks?s[expires_at_start]=' . date('Y/m-d H:i:s'));
        $I->seeResponseCodeIs(400);

        $I->sendGET('/tasks?s[expires_at_end]=' . date('Y-m-d H:i:s'));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([[
            'title' => 'Task 5',
        ]]);
        $I->dontSeeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],
            [
                'title' => 'Task 2',
            ],
        ]);

        $I->sendGET('/tasks?s[expires_at_end]=' . date('Y/m-d H:i:s'));
        $I->seeResponseCodeIs(400);

        $I->sendGET('/tasks?s[title]=Task&s[status_id]=1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Task 1',
            ],
        ]);
        $I->dontSeeResponseContainsJson([[
            'title' => 'Task 5',
        ]]);
    }
}
