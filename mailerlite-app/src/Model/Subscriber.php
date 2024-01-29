<?php

namespace App\Model;

use App\DTO\SubscriberDTO;
use PDO;

class Subscriber extends BaseModel
{
    protected string $table = 'subscribers';

    /**
     * @throws \Throwable
     * @return array<mixed>
     */
    public function store(SubscriberDTO $subscriberDTO): array
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO $this->table (email, name, last_name, status) VALUES (:email, :name, :last_name, :status)";

            $this->connection->query($sql, [
                'email' => $subscriberDTO->email,
                'name' => $subscriberDTO->name,
                'last_name' => $subscriberDTO->last_name,
                'status' => $subscriberDTO->status->value
            ]);

            $id = $this->connection->getLastInsertedId();

            $this->connection->commit();

        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw $e;
        }

        return  [
            'id' => $id,
            'email' => $subscriberDTO->email,
            'name' => $subscriberDTO->name,
            'last_name' => $subscriberDTO->last_name,
            'status' => $subscriberDTO->status->value,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * @param int $from
     * @param int $size
     * @return array<mixed>
     */
    public function getSubscribers(int $from, int $size): array
    {
        $sql = "SELECT * FROM $this->table LIMIT :from, :size";

        $stmt = $this->connection->query($sql, [
            'from' => $from,
            'size' => $size
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $email
     * @return array<mixed>|false
     */
    public function getSubscriberByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";

        $stmt = $this->connection->query($sql, [
            'email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSubscriberCountByEmail(string $email): mixed
    {
        $sql = "SELECT COUNT(*) FROM $this->table WHERE email = :email";

        $stmt = $this->connection->query($sql, [
            'email' => $email
        ]);

        return $stmt->fetchColumn();
    }

    public function getSubscribersCount(): mixed
    {
        $sql = "SELECT COUNT(*) FROM $this->table";

        $stmt = $this->connection->query($sql);

        return $stmt->fetchColumn();
    }
}
