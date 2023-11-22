<?php

namespace Cascata\Framework\Container;

use Cascata\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    public array $defaultPrimaryTypes = [
        "string" => "",
        "int" => 0,
        "float" => 0.0,
        "array" => [],
    ];
    private array $services = [];

    public function __construct()
    {
        $this->defaultPrimaryTypes["callable"] = function () {};
    }

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

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
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
     * @throws ReflectionException
     */
    private function resolve(string|object $class): object
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

    private function resolveClassDependencies(array $reflectionConstructorParams): array
    {
        // 1. Initialize empty dependencies array ( required by newInstanceArgs)
        $classDependencies = [];

        // 2. Try to locate and instanciate each parameter
        /** @var ReflectionParameter $parameter */
        foreach($reflectionConstructorParams as $parameter) {
            // Get the parameters ReflecationNamedType as $serviceType
            $serviceType = $parameter->getType();

            // Try to instanciate using $serviceType's name
            if($serviceType->allowsNull() === true) {
                continue;
            }

            if(!class_exists($serviceType->getName())) {
                $classDependencies[] = $this->defaultPrimaryTypes[$serviceType->getName()];
                continue;
            }

            $service = $this->get($serviceType->getName());

            $classDependencies[] = $service;
        }

        return $classDependencies;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}