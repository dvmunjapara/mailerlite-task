<?php

namespace App\DTO;

use App\Enums\SubscriberStatus;

class SubscriberDTO
{
    public function __construct(public readonly string $email,
                                public readonly string $name,
                                public readonly string $last_name,
                                public SubscriberStatus $status)
    {
    }
}
