<?php
declare(strict_types=1);

namespace www\extensions\api;

use www\extensions\api\exceptions\TodokeeperRequestException;
use www\extensions\api\exceptions\TodokeeperRuntimeException;

class FakeTodokeeper implements TodokeeperInterface
{

    /**
     * @param string $token
     */
    public function confirm(string $token) : void
    {
        if ($token === 'call_not_found') {
            throw new TodokeeperRequestException('Token not found or already expired');
        }

        if ($token === '') {
            throw new TodokeeperRequestException('Confirmation Token cannot be blank.');
        }

        if ($token === 'call_service_error') {
            throw new TodokeeperRuntimeException('Service is not available');
        }
    }
}
