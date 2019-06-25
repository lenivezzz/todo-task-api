<?php
declare(strict_types=1);

namespace www\extensions\api;

interface TodokeeperInterface
{
    /**
     * @param string $token
     */
    public function confirm(string $token) : void;
}
