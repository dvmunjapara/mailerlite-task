<?php

namespace App\Exceptions;

use Exception;

class SubscriberExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Subscriber already exists', 409);
    }
}
