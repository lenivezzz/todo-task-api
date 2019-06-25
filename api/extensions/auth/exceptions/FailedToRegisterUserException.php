<?php
declare(strict_types=1);

namespace api\extensions\auth\exceptions;

use RuntimeException;
use Throwable;

class FailedToRegisterUserException extends RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $msg = $message === '' ? 'Failed to register user' : $message;
        parent::__construct($msg, $code, $previous);
    }
}
