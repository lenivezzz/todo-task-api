<?php
declare(strict_types=1);

namespace api\tests\api;

use api\extensions\auth\fixtures\TokenFixture;
use api\fixtures\ProjectFixture;
use api\models\project\Project;
use api\tests\ApiTester;
use common\fixtures\UserFixture;

class UserprojectsCest
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
            ]
        ]);
    }

    public function access(ApiTester $I) : void
    {
        $I->sendGET('/projects');
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
    }

    public function index(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/projects');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Incoming',
                'is_default' => 1,
                'status_id' => 1,
            ],
            [
                'title' => 'Project 2',
                'is_default' => 0,
                'status_id' => 2,
            ],
        ]);
        $I->dontSeeResponseContainsJson([
            [
                'title' => 'Project 3',
                'is_default' => 0,
                'status_id' => 1,
            ],
        ]);
    }

    public function indexFilter(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/projects?s[statusId]=2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'title' => 'Project 2',
                'is_default' => 0,
                'status_id' => 2,
            ],
        ]);
        $I->dontSeeResponseContainsJson([
            [
                'title' => 'Project 3',
                'is_default' => 0,
                'status_id' => 1,
            ],
        ]);
        $I->dontSeeResponseContainsJson([
            [
                'title' => 'Incoming',
                'is_default' => 1,
                'status_id' => 1,
            ],
        ]);
    }

    public function view(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/projects/3');
        $I->seeResponseCodeIs(404);
        $I->sendGET('/projects/2');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'Project 2',
            'status_id' => 2,
            'is_default' => 0,
        ]);
    }

    public function create(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/projects', [
            'title' => ''
        ]);
        $I->seeResponseCodeIs(422);

        $title = 'New project' . time();
        $I->sendPOST('/projects', [
            'title' => $title
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'title' => $title,
            'status_id' => 1,
            'is_default' => 0,
        ]);
        $I->canSeeRecord(Project::class, [
            'status_id' => 1,
            'title' => $title,
            'is_default' => 0,
        ]);
    }

    public function update(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPATCH('/projects/100', [
            'title' => 'New project'
        ]);
        $I->seeResponseCodeIs(404);

        $I->sendPATCH('/projects/1', [
            'statusId' => 2,
        ]);
        $I->seeResponseCodeIs(403);

        $I->sendPATCH('/projects/2', [
            'title' => '',
        ]);
        $I->seeResponseCodeIs(422);

        $I->sendPATCH('/projects/2', [
            'title' => 'New title',
            'statusId' => 1,
        ]);
        $I->seeResponseCodeIs(200);
        $I->canSeeRecord(Project::class, [
            'id' => 2,
            'status_id' => 1,
            'title' => 'New title',
        ]);
    }

    public function deleteUnavailable(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendDELETE('/projects/2');
        $I->seeResponseCodeIs(404);
    }

    public function createOneMoreDefaultUnavailable(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendPOST('/projects', [
            'title' => 'One more default',
            'status_id' => 0,
            'is_default' => 1,
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'message' => 'User can have only one default project',
        ]);
    }
}
