<?php
declare(strict_types=1);

namespace App;

use App\Exceptions\ContainerException;
use App\Exceptions\RouteNotFoundException;
use ReflectionException;

class Router
{
    public function __construct(private readonly Container $container)
    {
    }

    private array $routes = [];

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    /**
     * @throws RouteNotFoundException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function resolve(string $requestUri, string $requestMethod): mixed
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (!$action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        [$class, $method] = $action;

        if (class_exists($class)) {
            $class = $this->container->get($class);

            if (method_exists($class, $method)) {
                return call_user_func([$class, $method], []);
            }
        }


        throw new RouteNotFoundException();
    }
}
