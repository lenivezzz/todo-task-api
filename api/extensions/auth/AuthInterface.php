<?php
declare(strict_types=1);

namespace api\extensions\auth;

use api\extensions\auth\models\Token;
use common\models\User;

interface AuthInterface
{
    /**
     * @param array $credentials
     * @return Token
     */
    public function authenticate(array $credentials) : Token;
}
