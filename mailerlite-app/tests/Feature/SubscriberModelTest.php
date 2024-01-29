<?php

use App\Database\Connection;
use App\DTO\SubscriberDTO;
use App\Enums\SubscriberStatus;
use App\Model\Subscriber;
use function Pest\Faker\fake;

beforeEach(function () {
    $connection = new Connection();

    $connection->query('TRUNCATE TABLE subscribers');
});

it('can store data', function () {

    $subscriber = new Subscriber();

    $email = fake()->email();

    $subscriberDTO = new SubscriberDTO($email, 'John', 'Doe', SubscriberStatus::ACTIVE);

    $subscriber->store($subscriberDTO);

    $connection = new Connection();

    $count = $connection->query('SELECT COUNT(*) FROM subscribers WHERE email = ?', [$email])->fetchColumn();

    $this->assertSame(1, $count);
});

it('can fetch subscribers', function () {

    $subscriber = new Subscriber();

    $email = fake()->email();

    $subscriberDTO = new SubscriberDTO($email, 'John', 'Doe', SubscriberStatus::ACTIVE);

    $subscriber->store($subscriberDTO);

    $response = $subscriber->getSubscribers(0, 10);

    $this->assertIsArray($response);

    $this->assertCount(1, $response);
});

it('can fetch subscriber by email', function () {

    $subscriber = new Subscriber();

    $email = fake()->email();

    $subscriberDTO = new SubscriberDTO($email, 'John', 'Doe', SubscriberStatus::ACTIVE);

    $subscriber->store($subscriberDTO);

    $response = $subscriber->getSubscriberByEmail($email);

    $this->assertIsArray($response);

    $this->assertSame($email, $response['email']);
});
