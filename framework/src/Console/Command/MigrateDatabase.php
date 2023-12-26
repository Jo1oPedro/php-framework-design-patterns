<?php

namespace Cascata\Framework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function __construct(
        private Connection $connection,
        private readonly string $migrationsPath
    )
    {}

    public function execute(array $params = []): int
    {
        try {
            // Create a migrations table if table are no already in existence
            $this->createMigrationsTable();

            $this->connection->beginTransaction();

            // Get $appliedMigrations which are already in the database.migrations table
            $appliedMigrations = $this->appliedMigrations();

            // Get the $migrationFiles from the migrations folder
            $migrationFiles = $this->getMigrationsFiles();

            // Get the migrations to apply. example: they are in $migrationFiles but not in $appliedMigrations
            $migrationsToApply = array_diff($migrationFiles, $appliedMigrations);

            $schema = new Schema();

            // Create SQL for any migrations which have not been run. example: which are not in the database
            $migrationsSql = [];
            foreach ($migrationsToApply as $migration) {
                // require the object
                $migrationObject = require $this->migrationsPath . "/{$migration}";

                // call up method
                $migrationsSql[] = $migrationObject->up($schema);

                // Add migration to database
                $this->insertMigration($migration);
            }
            //$migrationsSql = $schema->toSql($this->connection->getDatabasePlatform());

            // Add migration to database
            foreach($migrationsSql as $sql) {
                $this->connection->executeQuery($sql);
            }

            if(array_key_exists("seed", $params)) {
                shell_exec("php codejr run:seeders");
            }
            // Execute the sql query
            //$this->connection->commit();
            return 0;
        } catch (\Throwable $throwable) {
            $this->connection->rollBack();
            throw $throwable;
        }
    }

    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if(!$schemaManager->tablesExist('migrations')) {
            $schema = new Schema();
            $table = $schema->createTable('migrations');
            $table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
            $this->connection->executeQuery($sqlArray[0]);
        }
    }

    private function appliedMigrations(): array
    {
        $sql = "SELECT migration FROM migrations;";
        $appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();
        return $appliedMigrations;
    }

    private function getMigrationsFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);
        $filteredFiles = array_filter($migrationFiles, function($file) {
            return !in_array($file, ['.', '..']);
        });
        return $filteredFiles;
    }

    private function insertMigration(string $migration): void
    {
        $sql = "INSERT INTO migrations (migration) VALUES (?)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $migration);

        $stmt->executeStatement();
    }
}