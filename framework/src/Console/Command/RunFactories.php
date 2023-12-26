<?php

namespace Cascata\Framework\Console\Command;

use Cascata\Framework\Database\Factory\Faker;
use Cascata\Framework\GlobalContainer\Container;

class RunFactories implements CommandInterface
{
    private string $name = 'run:factories';

    public function execute(array $params = []): int
    {
        $basePath = Container::getInstance()->get('BASE_PATH');
        foreach($this->getFactories($basePath) as $factory) {
            $factoryName = str_replace(".php", "", $factory);
            $factoryClass = new \ReflectionClass("Database\Factories\\{$factoryName}");
            if($factoryClass->isSubclassOf(Faker::class)) {
                $reflectionMethod = $factoryClass->getMethod('create');
                $factoryInstance = $factoryClass->newInstance();
                $reflectionMethod->invoke($factoryInstance);
            }
        }
        return 0;
    }

    private function getFactories(string $basePath)
    {
        $onlyFactories = array_slice(scandir($basePath . "/database/factories"), 2);
        return $onlyFactories;
    }
}