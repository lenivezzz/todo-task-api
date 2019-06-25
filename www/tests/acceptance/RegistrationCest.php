<?php
declare(strict_types=1);

namespace www\tests\functional;

use www\tests\AcceptanceTester;
use yii\helpers\Url;

class RegistrationCest
{
    public function testTokenNotFound(AcceptanceTester $I) : void
    {
        $I->amOnUrl(Url::to(['/registration/confirm', 'token' => 'call_not_found'], true));
        $I->see('Token not found or already expired');
    }

    public function testFailedToConfirm(AcceptanceTester $I) : void
    {
        $I->amOnUrl(Url::to(['/registration/confirm', 'token' => 'call_service_error'], true));
        $I->see('Failed to confirm user');
    }

    public function testNoToken(AcceptanceTester $I) : void
    {
        $I->amOnUrl(Url::to(['/registration/confirm'], true));
        $I->see('Missing required parameters');

        $I->amOnUrl(Url::to(['/registration/confirm', 'token' => ''], true));
        $I->see('Confirmation Token cannot be blank.');
    }

    public function testVerified(AcceptanceTester $I) :void
    {
        $I->amOnUrl(Url::to(['/registration/confirm', 'token' => 'good_token'], true));
        $I->lookForwardTo('registration/confirmed');
        $I->see('User confirmation completed. Enjoy our service ;)');
    }
}
