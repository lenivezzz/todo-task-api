<?php
declare(strict_types=1);

namespace api\tests\unit;

use api\extensions\auth\events\handlers\UserRegisteredHandler;
use api\extensions\auth\events\UserRegistered;
use api\models\ApiUser;
use Codeception\Lib\Connector\Yii2\TestMailer;
use Codeception\Test\Unit;
use Faker\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use yii\mail\MessageInterface;
use yii\swiftmailer\Mailer;

class UserRegisteredHandlerTest extends Unit
{
    public function testOnUserRegistered() : void
    {
        /** @var ApiUser|MockObject $user */
        $user = $this->getMockBuilder(ApiUser::class)
            ->setMethods(['attributes'])
            ->getMock();
        $user->method('attributes')->willReturn(['id', 'email', 'confirmation_token']);
        $user->email = Factory::create()->email;
        $user->confirmation_token = 'confirmation_token';
        $event = new UserRegistered($user);

        $recipientEmail = null;
        $mailer = new class extends TestMailer {
            public function render($view, $params = [], $layout = false) : string
            {
                return '';
            }
        };
        $mailer->callback = function (MessageInterface $message) use (&$recipientEmail) {
            $recipientEmail = array_key_first($message->getTo());
        };
        $userRegisteredHandler = new UserRegisteredHandler($mailer);
        $userRegisteredHandler->onUserRegistered($event);
        $this->assertEquals($user->email, $recipientEmail);

        $mailer = new class extends Mailer {

            public function sendMessage($message) : bool
            {
                return false;
            }

            public function render($view, $params = [], $layout = false) : string
            {
                return '';
            }
        };
        $this->expectException(RuntimeException::class);
        $userRegisteredHandler = new UserRegisteredHandler($mailer);
        $userRegisteredHandler->onUserRegistered($event);
    }
}
