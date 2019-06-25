<?php
declare(strict_types=1);

namespace api\tests\unit;

use api\extensions\auth\exceptions\InvalidCredentialsException;
use api\extensions\auth\exceptions\UserNotFoundException;
use api\extensions\auth\UsernamePasswordAuthManager;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use yii\base\Security;

class UsernamePasswordAuthManagerTest extends Unit
{
    public function _fixtures() : array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ]
        ];
    }

    public function testAuthenticate() : void
    {
        /** @var Security|MockObject $security */
        $security = $this->getMockBuilder(Security::class)
            ->setMethods(['validatePassword', 'generateRandomString'])
            ->getMock();
        $security->method('validatePassword')->willReturn(true, false);
        $security->method('generateRandomString')->willReturn('secrettoken');

        $auth = new UsernamePasswordAuthManager($security);
        $token = $auth->authenticate(['username' => 'test2@mail.com', 'password' => 'Test1234']);
        $this->assertEquals('secrettoken', $token->token);

        $this->expectException(InvalidCredentialsException::class);
        $auth->authenticate(['username' => 'test2@mail.com', 'password' => 'incorrect-password']);
    }

    public function testAuthenticateUserNotFound() : void
    {
        /** @var Security|MockObject $security */
        $security = $this->getMockBuilder(Security::class)
            ->setMethods(null)
            ->getMock();
        $auth = new UsernamePasswordAuthManager($security);
        $this->expectException(UserNotFoundException::class);
        $auth->authenticate(['username' => 'not-exist-email@mail.com', 'password' => 'Test1234']);
    }

    public function testAuthenticateInvalidCredentialArgument() : void
    {
        /** @var Security|MockObject $security */
        $security = $this->getMockBuilder(Security::class)
            ->setMethods(null)
            ->getMock();
        $auth = new UsernamePasswordAuthManager($security);
        $this->expectException(InvalidArgumentException::class);
        $auth->authenticate([]);
    }
}
