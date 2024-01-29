<?php

beforeEach(function () {
    $this->client = new \GuzzleHttp\Client([
        'base_uri' => 'http://mailerlite-app-nginx',
        'http_errors' => false
    ]);

    $connection = new \App\Database\Connection();
    $connection->query('TRUNCATE TABLE subscribers');

    $cache = new \App\Cache\Cache();
    $cache->flush();
});

test('subscribe request validation', function () {

    $response = $this->client->post('/subscribe');

    $this->assertSame(422, $response->getStatusCode());

    $content = json_decode($response->getBody()->getContents(), true);

    $this->assertSame('Name is required', $content['error']);

    $response = $this->client->post('/subscribe', [
        'json' => [
            'name' => 'John',
            'last_name' => 'Doe',
        ]
    ]);

    $this->assertSame(422, $response->getStatusCode());

    $content = json_decode($response->getBody()->getContents(), true);

    $this->assertSame('Email is required', $content['error']);

    $response = $this->client->post('/subscribe', [
        'json' => [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john'
        ]
    ]);

    $this->assertSame(422, $response->getStatusCode());

    $content = json_decode($response->getBody()->getContents(), true);

    $this->assertSame('Please enter valid email', $content['error']);

    $response = $this->client->post('/subscribe', [
        'json' => [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'status' => 2
        ]
    ]);

    $this->assertSame(422, $response->getStatusCode());

    $content = json_decode($response->getBody()->getContents(), true);

    $this->assertSame('Please select correct status', $content['error']);
});

/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
test('subscriber should be unique', function () {


    $this->client->post('/subscribe', [
        'json' => [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'status' => 1
        ]
    ]);

    $response = $this->client->post('/subscribe', [
        'json' => [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'status' => 1
        ]
    ]);

    $this->assertSame(409, $response->getStatusCode());

    $content = json_decode($response->getBody()->getContents(), true);

    $this->assertSame('Subscriber already exists', $content['error']);
});
