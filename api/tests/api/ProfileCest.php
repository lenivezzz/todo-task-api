<?php
declare(strict_types=1);

namespace api\tests\api;

use api\extensions\auth\fixtures\TokenFixture;
use api\tests\ApiTester;
use common\fixtures\UserFixture;

class ProfileCest
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
        ]);
    }

    public function access(ApiTester $I) : void
    {
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
    }

    public function ownProfile(ApiTester $I) : void
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'email' => 'test@mail.com'
        ]);
        $I->dontSeeResponseContainsJson([
            'password_hash' => 'O87GkY3_UfmMHYkyezZ7QLfmkKNsllzT',
        ]);
    }
}
