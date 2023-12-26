<?php

namespace Cascata\Framework\Console\Command;

use Cascata\Framework\GlobalContainer\Container;

class MakeController implements CommandInterface
{
    private string $name = "make:controller";

    public function execute(array $params = []): int
    {
        $content = file_get_contents(__DIR__ . "/../../structureTemplates/Controller/Controller.txt");

        if(in_array('resource', $params)) {
            $content = file_get_contents(__DIR__ . "/../../structureTemplates/Controller/ControllerResource.txt");
            $content = str_replace(["{nameHolder}"], [$params["name"]], $content);
            file_put_contents(Container::getInstance()->get('BASE_PATH') . "/src/Controller/{$params["name"]}.php", $content);
            return 0;
        }

        $content = str_replace(["{nameHolder}"], [$params["name"]], $content);
        file_put_contents(Container::getInstance()->get('BASE_PATH') . "/src/Controller/{$params["name"]}.php", $content);
        return 0;
    }
}