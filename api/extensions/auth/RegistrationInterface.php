<?php
declare(strict_types=1);

namespace api\extensions\auth;

use api\models\ApiUser;

interface RegistrationInterface
{
    /**
     * @param array $params
     * @return ApiUser
     */
    public function register(array $params) : ApiUser;

    /**
     * @param string $token
     */
    public function confirm(string $token) : void;
}
