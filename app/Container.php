<?php
declare(strict_types=1);

namespace App;

use App\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Container implements ContainerInterface
{
    private array $entries = [];

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];
            return $entry($this);
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     * @throws ReflectionException|ContainerException
     */
    public function resolve(string $className): mixed
    {
        // 1. Inspect the class that we are trying to get from the container
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class $className is not instantiable");
        }

        // 2. Inspect the constructor of the class
        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $className;
        }

        // 3. Inspect the constructor parameters (dependencies)
        $parameters = $constructor->getParameters();
        if (empty($parameters)) {
            return new $className;
        }

        // 4. If the constructor parameter is a class, then try to resolve that class using the container
        $dependencies = array_map(function (ReflectionParameter $parameter) use ($className) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (!$type) {
                throw new ContainerException(sprintf("Failed to resolve class %s: parameter %s has no type", $className, $name));
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException(sprintf("Failed to resolve class %s: parameter %s has union type", $className, $name));
            }

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException(sprintf("Failed to resolve class %s: parameter %s is invalid", $className, $name));
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }
}
