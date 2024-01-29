<?php

namespace App\Database;

use PDO;
use PDOException;
use PDOStatement;

class Connection
{
    private string $host = 'mailerlite-app-mysql';

    private string $username = 'mailerlite';

    private string $password = 'mailerlite';

    private string $db_name = 'mailerlite';

    private ?PDO $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param string $sql
     * @param array<mixed> $params
     * @return PDOStatement
     */
    public function query(string $sql, $params = []): PDOStatement
    {

        $stmt = $this->connection->prepare($sql);

        $stmt->execute($params);

        return $stmt;
    }

    public function getLastInsertedId(): false|string
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }
}
