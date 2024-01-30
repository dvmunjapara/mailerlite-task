<?php

use App\Routing\Exceptions\MethodNotFoundException;
use App\Routing\Exceptions\NotFoundHttpException;
use App\Routing\Router;
use App\Routing\RouteResolver;

beforeEach(function () {
    $this->routes = require 'routes.php';

    $connection = new \App\Database\Connection();
    $connection->query('TRUNCATE TABLE subscribers');

    $cache = new \App\Cache\Cache();
    $cache->flush();
});

it('throws not found exception on wrong method', function () {

    $resolver = new RouteResolver($this->routes);
    $router = new Router($resolver);

    $router->route('GET', '/subscribe');

})->throws(NotFoundHttpException::class)->group('router');

it('throws method not found exception on wrong method or controller', function () {

    $routes = [
        'GET /subscribe' => ['TestController', 'subscribe'],
    ];

    $resolver = new RouteResolver($routes);
    $router = new Router($resolver);

    $router->route('GET', '/subscribe');
})->throws(MethodNotFoundException::class);

it('can call the list route', function () {

    $resolver = new RouteResolver($this->routes);
    $router = new Router($resolver);

    $router->route('GET', '/subscribers');

    $this->expectOutputString('{"data":[],"meta":{"total":0,"page":1,"limit":10,"total_pages":0}}');
});


it('can call the get route', function () {

    $resolver = new RouteResolver($this->routes);
    $router = new Router($resolver);

    $subscriber = new \App\Model\Subscriber();

    $email = \Pest\Faker\fake()->email();
    $name = \Pest\Faker\fake()->name();
    $last_name = \Pest\Faker\fake()->lastName();
    $status = \App\Enums\SubscriberStatus::ACTIVE;

    $subscriberDTO = new \App\DTO\SubscriberDTO($email, $name, $last_name, $status);

    $subscriber->store($subscriberDTO);

    $router->route('GET', '/subscriber?email=' . $email);

    $this->expectOutputRegex('/{"data":{"id":\d+,"email":"' . $email . '","name":"' . $name . '","last_name":"' . $last_name . '","status":'. $status->value .',"created_at":".*","updated_at":".*"}}/');
});


it('can call the store route', function () {

    $resolver = new RouteResolver($this->routes);
    $router = new Router($resolver);

    $router->route('POST', '/subscribe');

    $this->assertSame(422, http_response_code());

    $this->expectOutputString('{"error":"Name is required"}');

});
