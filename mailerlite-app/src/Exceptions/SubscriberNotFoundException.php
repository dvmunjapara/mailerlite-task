<?php

namespace App\Exceptions;

use Exception;

class SubscriberNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Subscriber not found');
    }
}
