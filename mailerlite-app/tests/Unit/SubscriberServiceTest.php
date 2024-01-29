<?php

use App\Cache\Cache;
use App\DTO\SubscriberDTO;
use App\Enums\SubscriberStatus;
use App\Model\Subscriber;
use App\Service\SubscriberService;
use GuzzleHttp\Client;

beforeEach(function () {
    $cache = new Cache();

    $cache->flush();
});

uses()->group('service');

/**
 * @runTestsInSeparateProcesses
 */
it('can fetch subscribers', function () {

    $subscriber = Mockery::mock(Subscriber::class);

    $subscriber->shouldReceive('getSubscribers')
        ->with(0, 10)
        ->once()->andReturn([
            "data" => [
                [
                    "id" => 1,
                    "email" => "john@doe.com",
                    "name" => "John",
                    "last_name" => "Doe",
                    "status" => 1,
                    "created_at" => "2024-01-29 12:33:04",
                    "updated_at" => "2024-01-29 14:11:10"
                ]
            ],
            "meta" => [
                "total" => 1,
                "page" => 1,
                "limit" => 10,
                "total_pages" => 1
            ]
        ]);

    $service = new SubscriberService($subscriber);

    $response = $service->listSubscribers(['page' => 1, 'limit' => 10]);

    $this->assertEqualsCanonicalizing([
        "data" => [
            [
                "id" => 1,
                "email" => "john@doe.com",
                "name" => "John",
                "last_name" => "Doe",
                "status" => 1,
                "created_at" => "2024-01-29 12:33:04",
                "updated_at" => "2024-01-29 14:11:10"
            ]
        ],
        "meta" => [
            "total" => 1,
            "page" => 1,
            "limit" => 10,
            "total_pages" => 1
        ]
    ], $response);
});

it('can fetch subscriber by email', function () {

    $subscriber = Mockery::mock(Subscriber::class);

    $subscriber->shouldReceive('getSubscriberByEmail')
        ->with('john@doe.com')
        ->once()->andReturn([
            "id" => 1,
            "email" => "john@doe.com",
            "name" => "John",
            "last_name" => "Doe",
            "status" => 1,
            "created_at" => "2024-01-29 12:33:04",
            "updated_at" => "2024-01-29 14:11:10"
        ]);

    $service = new SubscriberService($subscriber);

    $response = $service->getSubscriberByEmail('john@doe.com');

    $this->assertSame([
        "id" => 1,
        "email" => "john@doe.com",
        "name" => "John",
        "last_name" => "Doe",
        "status" => 1,
        "created_at" => "2024-01-29 12:33:04",
        "updated_at" => "2024-01-29 14:11:10"
    ], $response);
});

it('can store subscriber', function () {

    $subscriber = Mockery::mock(Subscriber::class);

    $dto = new SubscriberDTO('john@doe.com', 'John', 'Doe', SubscriberStatus::from(1));

    $subscriber->shouldReceive('store')
        ->once()
        ->with(Mockery::on(function ($arg) use ($dto) {
            return $arg->email === $dto->email
                && $arg->name === $dto->name
                && $arg->last_name === $dto->last_name
                && $arg->status->value === $dto->status->value;
        }))
        ->andReturn([
            "id" => 1,
            "email" => "john@doe.com",
            "name" => "John",
            "last_name" => "Doe",
            "status" => 1,
            "created_at" => "2024-01-29 12:33:04",
            "updated_at" => "2024-01-29 14:11:10"
        ]);

    $service = new SubscriberService($subscriber);

    $response = $service->storeSubscriber([
        "name" => "John",
        "last_name" => "Doe",
        "email" => "john@doe.com",
        "status" => 1
    ]);

    $this->assertSame([
        "id" => 1,
        "email" => "john@doe.com",
        "name" => "John",
        "last_name" => "Doe",
        "status" => 1,
        "created_at" => "2024-01-29 12:33:04",
        "updated_at" => "2024-01-29 14:11:10"
    ], $response);
});
