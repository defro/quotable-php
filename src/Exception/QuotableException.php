<?php

namespace Defro\Quotable\Exception;

use RuntimeException;
use Throwable;

class QuotableException extends RuntimeException
{
    public function __construct(
        string $message,
        int $statusCode = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }
}
