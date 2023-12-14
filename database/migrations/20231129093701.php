<?php

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;

return new class
{
    private $tableName = "user";
    public function up(Schema $schema): string
    {
        return "CREATE TABLE user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            idade INT,
            email VARCHAR(255) UNIQUE
        );";
    }

    public function down(AbstractSchemaManager $schemaManager): void
    {
        if($schemaManager->tablesExist($this->tableName)) {
            $schemaManager->dropTable($this->tableName);
        }
    }
};