<?php

namespace Websecret\LaravelGoogleApi\Exceptions;

use Throwable;
use Exception;

class AuthorizationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}