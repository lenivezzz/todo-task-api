<?php
declare(strict_types=1);

namespace api\extensions\auth;

use api\extensions\auth\exceptions\InvalidCredentialsException;
use api\extensions\auth\exceptions\FailedToSaveTokenException;
use api\extensions\auth\exceptions\UserNotFoundException;
use api\extensions\auth\models\Token;
use common\models\User;
use InvalidArgumentException;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\Security;

class UsernamePasswordAuthManager extends BaseObject implements AuthInterface
{
    /**
     * @var int
     */
    public $tokenDuration = 84600;
    /**
     * @var Security
     */
    private $security;

    /**
     * @param Security $security
     * @param array $config
     */
    public function __construct(Security $security, array $config = [])
    {
        parent::__construct($config);
        $this->security = $security;
    }

    /**
     * @inheritDoc
     * @throws Exception
     * @throws \Exception
     */
    public function authenticate(array $credentials) : Token
    {
        if (!isset($credentials['username'], $credentials['password'])) {
            throw new InvalidArgumentException('Invalid credentials.');
        }

        $user = User::findOne([
            'email' => $credentials['username'],
            'status' => User::STATUS_ACTIVE,
        ]);

        if (!$user) {
            throw new UserNotFoundException(sprintf('User "%s" not found', $credentials['username']));
        }

        if (!$this->security->validatePassword($credentials['password'], $user->password_hash)) {
            throw new InvalidCredentialsException('Password is incorrect');
        }

        $token = new Token();
        $token->setAttributes([
            'token' => $this->security->generateRandomString(),
            'expires_at' => date('Y-m-d H:i:s', time() + $this->tokenDuration),
            'user_id' => $user->id,
        ]);

        if (!$token->save()) {
//            @todo log errors
            throw new FailedToSaveTokenException();
        }

        return $token;
    }
}
