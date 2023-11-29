<?php

namespace Cascata\Framework\Console\Command;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function execute(array $params = []): int
    {
        dd($params);
        echo 'executando migration' . PHP_EOL;

        return 0;
    }
}