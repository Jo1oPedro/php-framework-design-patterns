<?php

namespace Cascata\Framework\Console\Command;

use Doctrine\DBAL\Connection;

class RollbackDatabase implements CommandInterface
{
    private string $name = 'database:migrations:rollback';

    public function __construct(
        private Connection $connection,
        private readonly string $migrationsPath
    ) {}

    public function execute(array $params = []): int
    {
        // Get all migrations
        $migrationsFiles = array_filter(scandir($this->migrationsPath), function ($migrationFile) {
            return !in_array($migrationFile, ['.', '..']);
        });
        $schema = $this->connection->createSchemaManager();
        // Try to run down all migration
        try {
            $this->connection->beginTransaction();
            foreach ($migrationsFiles as $migrationsFile) {
                $migration = require $this->migrationsPath . "/{$migrationsFile}";
                $migration->down($schema);
            }
            //$this->connection->commit();

            return 0;
        } catch (\Throwable $throwable) {
            $this->connection->rollBack();

            throw $throwable;
        }
    }
}