<?php
declare(strict_types=1);

namespace www\extensions\api\exceptions;

use Throwable;

class UnexpectedResponseStatusException extends TodokeeperException
{
    public function __construct(int $responseCode, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Unexpected status code %s', $responseCode), $code, $previous);
    }
}
