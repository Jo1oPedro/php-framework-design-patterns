<?php

namespace Cascata\Framework\Console\Command;

use Cascata\Framework\GlobalContainer\Container;

class RunSeedes implements CommandInterface
{
    private $name = 'run:seeders';

    public function execute(array $params = []): int
    {
        $basePath = Container::getInstance()->get('BASE_PATH');
        foreach($this->getSeeders($basePath) as $seeder) {
            $seederName = str_replace(".php", "", $seeder);
            $reflectionSeeder = new \ReflectionClass("Database\Seeders\\$seederName");
            $reflectionMethod = $reflectionSeeder->getMethod('run');
            $reflectionMethod->invoke($reflectionSeeder->newInstance());
        }
        return 0;
    }

    private function getSeeders(string $basePath): array
    {
        $seeders = array_slice(scandir($basePath . "/database/seeders"), 2);
        return $seeders;
    }
}