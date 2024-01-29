<?php

use App\Routing\Exceptions\MethodNotFoundException;
use App\Routing\Exceptions\NotFoundHttpException;
use App\Routing\Router;
use App\Routing\RouteResolver;

beforeEach(function () {
    $this->routes = require 'routes.php';
});

it('throws not found exception on wrong method', function () {

    $routes = require_once 'routes.php';

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
