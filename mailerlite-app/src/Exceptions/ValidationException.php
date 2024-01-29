<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{

    public function __construct(string $message = "Validation error")
    {
        parent::__construct($message, 422);
    }
}
