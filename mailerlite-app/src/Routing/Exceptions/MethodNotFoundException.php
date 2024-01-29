<?php

namespace App\Routing\Exceptions;

use Exception;

class MethodNotFoundException extends Exception
{
    public function __construct(string $message)
    {

        parent::__construct($message, 405);
    }
}
