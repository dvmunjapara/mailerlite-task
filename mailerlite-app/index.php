<?php

require_once 'vendor/autoload.php';

use App\Routing\Router;
use App\Routing\RouteResolver;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$routes = require_once 'routes.php';

$resolver = new RouteResolver($routes);
$router = new Router($resolver);

try {
    $router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code($e->getCode());

    echo json_encode([
        'message' => $e->getMessage()
    ]);

}
