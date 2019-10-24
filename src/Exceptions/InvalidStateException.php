<?php

namespace Websecret\LaravelGoogleApi\Exceptions;

use Exception;
use Throwable;

class InvalidStateException extends Exception
{
    public function __construct($message = "Invalid State", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}