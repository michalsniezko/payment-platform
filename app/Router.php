<?php
declare(strict_types=1);

namespace App;

use App\Attributes\Route;
use App\Exceptions\ContainerException;
use App\Exceptions\RouteNotFoundException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class Router
{
    private array $routes = [];

    public function __construct(private readonly Container $container)
    {
    }

    /**
     * @throws ReflectionException
     */
    public function registerRoutesFromControllerAttributes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $reflectionController = new ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $reflectionMethod) {
                $attributes = $reflectionMethod->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    /** @var Route $route */
                    $route = $attribute->newInstance();
                    $this->register($route->requestMethod->value, $route->path, [$controller, $reflectionMethod->getName()]);
                }
            }

        }
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

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }
}
