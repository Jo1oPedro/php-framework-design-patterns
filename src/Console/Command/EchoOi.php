<?php

namespace App\Console\Command;

use Cascata\Framework\Console\Command\CommandInterface;

class EchoOi implements CommandInterface
{
    private string $name = "printaOi";

    public function execute(array $params = []): int
    {
        shell_exec("php codejr database:migrations:rollback");
        echo "printaOi";
        return 0;
    }
}