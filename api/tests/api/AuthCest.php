<?php
declare(strict_types=1);

namespace api\tests\api;

use api\extensions\auth\fixtures\TokenFixture;
use api\tests\ApiTester;
use common\fixtures\UserFixture;

class AuthCest
{
    public function _before(ApiTester $I) : void
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
            'token' => [
                'class' => TokenFixture::class,
                'dataFile' => codecept_data_dir() . 'token.php',
            ],
            'auth_data' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'auth_data.php',
            ],
        ]);
    }

    public function badMethod(ApiTester $I) : void
    {
        $I->sendGET('/auth');
        $I->seeResponseCodeIs(405);
        $I->seeResponseIsJson();
    }

    public function wrongData(ApiTester $I) : void
    {
        $I->sendPOST('/auth', [
            'username' => 'wrongname',
            'password' => 'wrongpassword',
        ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'password',
            'message' => 'Incorrect username or password.',
        ]);

        $I->sendPOST('/auth', [
            'username' => 'test@mail.com',
            'password' => 'Test1234',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'password',
            'message' => 'Incorrect username or password.',
        ]);
    }

    public function successLogin(ApiTester $I) : void
    {
        $I->sendPOST('/auth', [
            'username' => 'test2@mail.com',
            'password' => 'Test1234',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token', '$.expires_at');
    }

    public function authenticated(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/logout/ping');
        $I->seeResponseCodeIs(200);
    }

    public function expired(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-expired');
        $I->sendGET('/logout/ping');
        $I->seeResponseCodeIs(401);
    }

    public function inactiveUser(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('inactive-user-token');
        $I->sendGET('/logout/ping');
        $I->seeResponseCodeIs(401);
    }
}
