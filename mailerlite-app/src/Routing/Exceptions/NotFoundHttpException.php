<?php

namespace App\Routing\Exceptions;

use Exception;

class NotFoundHttpException extends Exception
{
    public function __construct(string $message = "Page not found")
    {
        parent::__construct($message, 404);
    }
}
