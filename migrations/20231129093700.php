<?php

use Doctrine\DBAL\Schema\Schema;

return new class
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('posts');
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('title', Types::STRING, ['length' => 255]);
        $table->addColumn('body', Types::TEXT);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        echo get_class($this) . ' "down" method called' . PHP_EOL;
    }
};