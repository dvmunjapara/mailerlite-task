<?php

namespace App\Routing;

class RouteResolver
{

    /**
     * @var string[] $routes
     */
    protected array $routes;

    /**
     * @param string[] $routes
     */
    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    /**
     * @param string $requestMethod
     * @param string $uri
     * @return array<mixed>|null
     */
    public function resolve(string $requestMethod, string $uri): ?array
    {
        $parts = parse_url($uri);
        parse_str($parts['query']??"", $params);

        if ($requestMethod === 'POST') {
            $payload = file_get_contents('php://input');

            if ($payload) {

                $data = json_decode($payload, true);
                $params = array_merge($params, $data);
            }
        }

        $url = explode('/', $parts['path']??"");

        $controller = $url[1] ?? '/'; // Use '/' if $url[1] is not set

        foreach ($this->routes as $route => $handler) {
            list($method, $pattern) = explode(' ', $route, 2);

            if ($pattern !== '/') {
                $pattern = rtrim($pattern, '/');
            }
            $pattern = str_replace('/', '\/', $pattern);

            if ($method == $requestMethod && preg_match("/^$pattern$/i", "/$controller")) {

                return ["App\Controller\\{$handler[0]}", $handler[1], $params];
            }
        }

        return null;
    }

}
