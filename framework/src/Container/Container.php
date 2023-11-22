<?php

namespace Cascata\Framework\Container;

use Cascata\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    /**
     * @throws ContainerException
     */
    public function add(string $id, string|object $concrete = null): void
    {
        if(is_null($concrete)) {
            if(!class_exists($id)) {
                throw new ContainerException("Serviço {$id} não foi encontrado");
            }
            $concrete = $id;
        }
        $this->services[$id] = $concrete;
    }

    public function get(string $id)
    {
        if(!$this->has($id)) {
            if(!class_exists($id)) {
                throw new ContainerException("Serviço {$id} não foi encontrado");
            }

            $this->add($id);
        }

        $object = $this->resolve($this->services[$id]);

        return $object;
    }

    /**
     * @throws \ReflectionException
     */
    private function resolve(string|object $class)
    {
        // 1 . Instanciate a Reflection class (dump and check)
        $reflectionClass = new \ReflectionClass($class);

        // 2 . Use Reflection to try to obtain a class constructor.
        $constructor = $reflectionClass->getConstructor();

        // 3 . If there is no constructor, simply instanciate.
        if(is_null($constructor)) {
            return $reflectionClass->newInstance();
        }

        // 4 . Get the constructor parameters
        $constructorParams = $constructor->getParameters();

        // 5 . Obtain dependencies
        $classDepencies = $this->resolveClassDependencies($constructorParams);

        // 6 . Instanciate with depencies
        $service = $reflectionClass->newInstanceArgs($classDepencies);

        // 7 . Return the object
        return $service;
    }

    private function resolveClassDependencies($constructorParams)
    {

    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}