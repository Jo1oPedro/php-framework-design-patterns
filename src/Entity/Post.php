<?php

namespace App\Entity;

use DateTimeImmutable;

class Post
{
    public function __construct(
      private ?int $id,
      private string $title,
      private string $body,
      private \DateTimeImmutable $createdAt
    ) {}

    public static function create(
        string $title,
        string $body,
        ?int $id = null,
        DateTimeImmutable $createdAt = new DateTimeImmutable()
    ): Post
    {
        return new self($id, $title, $body, $createdAt);
    }
}