<?php

namespace App\Repository;

use App\Entity\Post;
use Cascata\Framework\Http\Exceptions\NotFoundException;
use Cascata\Framework\Repository\Repository;
use Doctrine\DBAL\Connection;

class PostRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function findOrFail(int $id): Post
    {
        if(!$post = $this->findById($id)) {
            throw new NotFoundException(sprintf('Post com id %d não encontrado', $id));
        }
        return $post;
    }

    public function findById(int $id): ?Post
    {
        $stmt = $this->connection->executeQuery(
            "SELECT * FROM posts WHERE id = :id",
            ['id' => $id]
        );

        $row = $stmt->fetchAssociative();

        /*$queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeStatement();

        $result = $queryBuilder->executeQuery();

        $row = $result->fetchAssociative());*/

        if(!$row) {
            return null;
        }

        $post = new Post(
            id: $row['id'],
            title: $row['title'],
            body: $row['body'],
            createdAt: new \DateTimeImmutable($row['created_at'])
        );

        return $post;
    }
}