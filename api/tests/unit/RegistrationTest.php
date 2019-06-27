<?php
declare(strict_types=1);

namespace api\tests\unit;

use api\extensions\auth\exceptions\FailedToRegisterUserException;
use api\extensions\auth\exceptions\UserNotFoundException;
use api\extensions\auth\Registration;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class RegistrationTest extends Unit
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

    /**
     * @throws Exception
     */
    public function testRegister() : void
    {
        $registration = new Registration(Yii::$app->security, $this->createLogger());
        $user = $registration->register([
            'email' => 'correctemail@todoemail.com',
            'username' => 'correctusername',
            'password' => 'correct_password',
        ]);
        $this->assertEquals(
            [
                'email' => 'correctemail@todoemail.com',
                'username' => 'correctusername',
                'status' => 1,
            ],
            [
                'email' => $user->email,
                'username' => $user->username,
                'status' => $user->status,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function testInvalidParams() : void
    {
        $logger = $this->createLogger();
        $registration = new Registration(Yii::$app->security, $logger);
        try {
            $registration->register([]);
        } catch (FailedToRegisterUserException $e) {
            $this->assertStringStartsWith('Invalid params list:', array_shift($logger->log)['message']);
        }
        $this->expectException(FailedToRegisterUserException::class);
        $registration->register([]);
    }

    /**
     * @throws Exception
     */
    public function testIncorrectParams() : void
    {
        $logger = $this->createLogger();
        $registration = new Registration(Yii::$app->security, $logger);
        try {
            $registration->register([
                'email' => '',
                'password' => '',
                'username' => '',
            ]);
        } catch (FailedToRegisterUserException $e) {
            $this->assertStringStartsWith('Failed to save user. Errors: ', array_shift($logger->log)['message']);
        }
        $this->expectException(FailedToRegisterUserException::class);
        $registration->register([
            'email' => '',
            'password' => '',
            'username' => '',
        ]);
    }

    public function testConfirm() : void
    {
        $registration = new Registration(
            Yii::$app->security,
            $this->createLogger()
        );

        $token = 'verificationtoken';
        $user = User::findOne([
            'confirmation_token' => $token,
        ]);

        $confirmedUser = $registration->confirm($token);
        $this->assertEquals($user->id, $confirmedUser->id);

        $this->expectException(UserNotFoundException::class);
        $registration->confirm('invalidtoken');
    }

    /**
     * @return Logger
     */
    private function createLogger()
    {
        return new class extends Logger {
            public $log = [];

            public function log($message, $level, $category = 'application')
            {
                $this->log[] = [
                    'level' => $level,
                    'message' => $message,
                ];
            }
        };
    }
}
