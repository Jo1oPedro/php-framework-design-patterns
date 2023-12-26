<?php

namespace Cascata\Framework\Console\Command;

use Cascata\Framework\GlobalContainer\Container;

class MakeFactory implements CommandInterface
{
    private string $name = "make:factory";

    public function execute(array $params = []): int
    {
        $content = file_get_contents(__DIR__ . "/../../structureTemplates/Factory/Factory.txt");
        $content = str_replace(["{nameHolder}"], [$params["name"]], $content);
        $basePath = Container::getInstance()->get('BASE_PATH');
        file_put_contents($basePath . "/database/factories/{$params["name"]}.php", $content);
        return 0;
    }
}