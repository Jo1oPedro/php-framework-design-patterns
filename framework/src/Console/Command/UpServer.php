<?php

namespace Cascata\Framework\Console\Command;

class UpServer implements CommandInterface
{
    private string $name = "server";

    public function execute(array $params = []): int
    {
        $params['port'] ??= "8080";
        shell_exec("php -S localhost:{$params['port']} -t public public/index.php");
        return 0;
    }
}