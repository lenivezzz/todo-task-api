<?php
declare(strict_types=1);

namespace api\tests\api;

use api\models\ApiUser;
use api\tests\ApiTester;
use common\fixtures\UserFixture;

class RegistrationCest
{
    public function _before(ApiTester $I) : void
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ]);
    }
    public function testUserSaved(ApiTester $I) : void
    {
        $I->sendPOST('/registration', [
            'username' => 'correctusername',
            'email' => 'correctemail@todoemail.com',
            'password' => 'StrongPassword1',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'username' => 'correctusername',
            'email' => 'correctemail@todoemail.com',
        ]);

        $user = ApiUser::findOne([
            'email' => 'correctemail@todoemail.com'
        ]);

        !$user && assertTrue(false, 'User not saved');
    }

    public function testUserValidation(ApiTester $I) : void
    {
        $I->sendGET('/registration');
        $I->seeResponseCodeIs(405);

        $I->sendPOST('/registration', []);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            [
                'field' => 'email',
                'message' => 'Email cannot be blank.'
            ],
            [
                'field' => 'username',
                'message' => 'Username cannot be blank.'
            ],
            [
                'field' => 'password',
                'message' => 'Password cannot be blank.'
            ]
        ]);

        $I->sendPOST('/registration', [
            'username' => 'correctusername',
            'email' => 'incorrectemail',
            'password' => '1234567',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            [
                'field' => 'email',
                'message' => 'Email is not a valid email address.'
            ],
            [
                'field' => 'password',
                'message' => 'Password should contain at least 8 characters.'
            ],
        ]);

        $I->sendPOST('/registration', [
            'username' => 'correctusername',
            'email' => 'test2@mail.com',
            'password' => 'StrongPassword1',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            [
                'field' => 'email',
                'message' => 'Email "test2@mail.com" has already been taken.',
            ],
        ]);
    }

    public function testUserConfirmed(ApiTester $I) : void
    {
        $I->sendPOST('/registration/confirm', [
            'confirmationToken' => 'verificationtoken',
        ]);
        $I->seeResponseCodeIs(204);
    }

    public function testConfirmationValidation(ApiTester $I) : void
    {
        $I->sendGET('/registration/confirm');
        $I->seeResponseCodeIs(405);

        $I->sendPOST('/registration/confirm');
        $I->seeResponseCodeIs(422);

        $I->sendPOST('/registration/confirm', [
            'confirmationToken' => 'wrongconfirmationtoken',
        ]);
        $I->seeResponseCodeIs(422);
    }
}
