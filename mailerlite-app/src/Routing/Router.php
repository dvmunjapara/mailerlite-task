<?php

namespace App\Routing;

use App\Routing\Exceptions\MethodNotFoundException;
use App\Routing\Exceptions\NotFoundHttpException;

class Router {
    public function __construct(protected RouteResolver $resolver) {

    }

    /**
     * @throws NotFoundHttpException
     * @throws MethodNotFoundException
     */
    public function route(string $method, string $uri): void
    {
        $result = $this->resolver->resolve($method, $uri);

        if ($result) {
            list($controller, $method, $params) = $result;

            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                $callback = [$controllerInstance, $method];

                if (is_callable($callback)) {
                    call_user_func_array($callback, $params ? [$params] : []);
                }
            } else {
                throw new MethodNotFoundException("$controller not found");
            }
        } else {
            throw new NotFoundHttpException();
        }
    }
}
