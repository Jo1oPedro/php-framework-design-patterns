<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class
{
    public function up(Schema $schema): string
    {
        return "CREATE TABLE user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            idade INT,
            email VARCHAR(255) UNIQUE
        );";
    }

    public function down(): void
    {
        echo get_class($this) . ' "down" method called' . PHP_EOL;
    }
};