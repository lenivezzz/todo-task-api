<?php
declare(strict_types=1);

namespace api\extensions\auth;

use api\extensions\auth\exceptions\FailedToRegisterUserException;
use api\extensions\auth\exceptions\UserNotFoundException;
use api\models\ApiUser;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\Security;
use yii\log\Logger;

class Registration extends BaseObject implements RegistrationInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Security $security
     * @param Logger $logger
     * @param array $config
     */
    public function __construct(Security $security, Logger $logger, array $config = [])
    {
        parent::__construct($config);
        $this->security = $security;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @return ApiUser
     * @throws Exception
     */
    public function register(array $params) : ApiUser
    {
        $this->ensureParamsIsValid($params);
        $user = new ApiUser();
        $user->setAttributes([
            'email' => $params['email'],
            'username' => $params['username'],
            'password_hash' => $this->security->generatePasswordHash($params['password']),
            'status' => ApiUser::STATUS_UNVERIFIED,
            'confirmation_token' => $this->security->generateRandomString(64),
        ]);

        if (!$user->save()) {
            $this->logger->log(
                sprintf('Failed to save user. Errors: %s', var_export($user->getErrors(), true)),
                $this->logger::LEVEL_ERROR
            );
            throw new FailedToRegisterUserException();
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $token) : ApiUser
    {
        $user = ApiUser::findOne([
            'status' => ApiUser::STATUS_UNVERIFIED,
            'confirmation_token' => $token,
        ]);

        if (!$user) {
            throw new UserNotFoundException(sprintf('User with token "%s" not found', $token));
        }

        $user->setAttributes([
            'confirmation_token' => '',
            'status' => ApiUser::STATUS_ACTIVE,
        ]);

        if (!$user->save()) {
            $this->logger->log(
                sprintf('Failed to save user. Errors: %s', var_export($user->getErrors(), true)),
                $this->logger::LEVEL_ERROR
            );
            throw new FailedToRegisterUserException();
        }

        return $user;
    }

    /**
     * @param array $params
     */
    private function ensureParamsIsValid(array $params) : void
    {
        if (!isset($params['email'], $params['username'], $params['password'])) {
            isset($params['password']) && $params['password'] !== '' && $params['password'] = 'hidden_value';
            $this->logger->log(
                sprintf('Invalid params list: %s', var_export($params, true)),
                $this->logger::LEVEL_ERROR
            );
            throw new FailedToRegisterUserException();
        }
    }
}
