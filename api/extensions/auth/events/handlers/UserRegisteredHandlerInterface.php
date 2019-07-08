<?php
declare(strict_types=1);

namespace api\extensions\auth\events\handlers;

use api\extensions\auth\events\UserRegistered;

interface UserRegisteredHandlerInterface
{
    /**
     * @param UserRegistered $event
     */
    public function onUserRegistered(UserRegistered $event) : void;
}
