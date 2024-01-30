<?php


it('can connect to database', function () {
    $connection = new \App\Database\Connection();

    $this->assertInstanceOf(PDO::class, $connection->getConnection());
});

it('can query database', function () {
    $connection = new \App\Database\Connection();

    $this->assertInstanceOf(PDOStatement::class, $connection->query('Select 1 from dual'));
});

it('can get last inserted id', function () {
    $connection = new \App\Database\Connection();

    $connection->query('CREATE TABLE IF NOT EXISTS `groups` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

    $connection->query('INSERT INTO `groups` (`name`) VALUES (?)', ['test']);

    $this->assertIsString($connection->getLastInsertedId());

    $connection->query('DROP TABLE `groups`');
});

it('can begin transaction', function () {
    $connection = new \App\Database\Connection();

    $this->assertTrue($connection->beginTransaction());
});

it('can commit transaction', function () {
    $connection = new \App\Database\Connection();

    $connection->getConnection()->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

    $connection->beginTransaction();

    try {

        $connection->query('CREATE TABLE IF NOT EXISTS `groups` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

        $connection->query('INSERT INTO `groups` (`name`) VALUES (?)', ['test']);

        $connection->commit();

        $connection->getConnection()->setAttribute(PDO::ATTR_AUTOCOMMIT, true);

    } catch (\Throwable $th) {
        throw $th;
    }

    $count = $connection->query('SELECT COUNT(*) FROM `groups`')->fetchColumn();

    $this->assertSame(1, $count);

    $connection->query('DROP TABLE `groups`');
});

it('can rollback transaction', function () {
    $connection = new \App\Database\Connection();

    $connection->getConnection()->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

    $connection->beginTransaction();

    try {

        $connection->query('CREATE TABLE IF NOT EXISTS `groups` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

        $connection->query('INSERT INTO `groups` (`name`) VALUES (?)', ['test']);

        $connection->rollBack();

    } catch (\Throwable $th) {
        throw $th;
    }

    $count = $connection->query('SELECT COUNT(*) FROM `groups`')->fetchColumn();

    $this->assertSame(0, $count);

    $connection->query('DROP TABLE `groups`');

    $connection->getConnection()->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
});

