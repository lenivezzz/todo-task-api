<?php
declare(strict_types=1);

namespace api\extensions\auth\exceptions;

use RuntimeException;
use Throwable;

class FailedToSaveTokenException extends RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $message === '' && $message = 'Failed to save token';
        parent::__construct($message, $code, $previous);
    }
}
