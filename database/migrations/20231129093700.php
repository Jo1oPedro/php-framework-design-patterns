<?php

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class
{
    private $tableName = "migrations";

    public function up(Schema $schema): string //void
    {
        return "select * from migrations";
        /*$table = $schema->createTable('posts');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('title', Types::STRING, ['length' => 255]);
        $table->addColumn('body', Types::TEXT);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);*/
    }

    public function down(AbstractSchemaManager $schemaManager): void
    {
        if($schemaManager->tablesExist($this->tableName)) {
            $schemaManager->dropTable($this->tableName);
        }
    }
};