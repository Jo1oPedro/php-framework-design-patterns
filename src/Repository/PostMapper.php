<?php

namespace App\Repository;

use App\Entity\Post;
use Cascata\Framework\Repository\Mapper;
use Doctrine\DBAL\Connection;

class PostMapper
{
    public function __construct(private Connection $connection)
    {

    }

    public function save(Post $post): void
    {
        $stmt = $this->connection->prepare(
            "
                INSERT INTO posts(title, body, created_at)
                VALUES(:title, :body, :created_at)
            "
        );

        $stmt->bindValue(':title', $post->getTitle());
        $stmt->bindValue(':body', $post->getTitle());
        $stmt->bindValue(':created_at', $post->getCreatedAt()->format('Y-m-d H:i:s'));

        $stmt->executeStatement();

        $post->setId($this->connection->lastInsertId());
    }
}