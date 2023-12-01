<?php

namespace App\Console\Command;

use Cascata\Framework\Console\Command\CommandInterface;

class PrintaCoito implements CommandInterface
{
    private string $name = "printaCoito";

    public function execute(array $params = []): int
    {
        echo "oi coito";
        return 0;
    }
}