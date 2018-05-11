<?php

namespace Websecret\LaravelGoogleApi\Exceptions;

use Throwable;
use Exception;

class InvalidStateException extends Exception
{
    public function __construct(string $message = "Invalid state", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}