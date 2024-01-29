<?php

namespace App\Model;

use App\Database\Connection;
use PDO;

class BaseModel
{
    protected string $table;

    protected Connection $connection;

    public function __construct() {
        $this->connection = new Connection();
    }
}
