<?php

use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->base_url = 'http://mailerlite-app.localhost';
});

it('can list subscribers', function () {

    $string = json_encode([
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

    $response = new Response(200, ['Content-Type' => 'application/json'], $string);

    $guzzle = Mockery::mock(GuzzleHttp\Client::class);
    $guzzle->shouldReceive('get')->once()->andReturn($response);

    $client = $guzzle->get("$this->base_url/subscribers");

    $this->assertSame(200, $client->getStatusCode());

    $content = json_decode($client->getBody()->getContents(), true);

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
    ], $content);
});


it('can fetch subscriber by email', function () {

    $string = json_encode([
        "data" => [
            "id" => 1,
            "email" => "john@doe.com",
            "name" => "John",
            "last_name" => "Doe",
            "status" => 1,
            "created_at" => "2024-01-29 14:56:00",
            "updated_at" => "2024-01-29 14:56:00"
        ]
    ]);

    $response = new Response(200, ['Content-Type' => 'application/json'], $string);

    $guzzle = Mockery::mock(GuzzleHttp\Client::class);
    $guzzle->shouldReceive('get')->once()->andReturn($response);

    $client = $guzzle->get("$this->base_url/subscriber", [
        'query' => [
            'email' => 'john@doe.com'
        ]
    ]);

    $this->assertSame(200, $client->getStatusCode());

    $content = json_decode($client->getBody()->getContents(), true);

    $this->assertEqualsCanonicalizing([
        "data" => [
            "id" => 1,
            "email" => "john@doe.com",
            "name" => "John",
            "last_name" => "Doe",
            "status" => 1,
            "created_at" => "2024-01-29 14:56:00",
            "updated_at" => "2024-01-29 14:56:00"
        ]
    ], $content);
});

it('can store subscriber', function () {

    $string = json_encode([
        "message" => "Subscriber created successfully",
        "data" => [
            "id" => 1,
            "email" => "john@doe.com",
            "name" => "John",
            "last_name" => "Doe",
            "status" => 1,
            "created_at" => "2024-01-29 14:56:00",
            "updated_at" => "2024-01-29 14:56:00"
        ]
    ]);

    $response = new Response(200, ['Content-Type' => 'application/json'], $string);

    $guzzle = Mockery::mock(GuzzleHttp\Client::class);
    $guzzle->shouldReceive('post')->once()->andReturn($response);

    $client = $guzzle->post("$this->base_url/subscribe", [
        'json' => [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'status' => 1
        ]
    ]);

    $this->assertSame(200, $client->getStatusCode());

    $content = json_decode($client->getBody()->getContents(), true);

    $this->assertEqualsCanonicalizing([
        "message" => "Subscriber created successfully",
        "data" => [
            "id" => 1,
            "email" => "john@doe.com",
            "name" => "John",
            "last_name" => "Doe",
            "status" => 1,
            "created_at" => "2024-01-29 14:56:00",
            "updated_at" => "2024-01-29 14:56:00"
        ]
    ], $content);
});
