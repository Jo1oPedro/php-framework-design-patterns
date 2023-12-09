<?php

namespace Cascata\Framework\Http;

class Response
{
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public function __construct(
        private ?string $content = null,
        private int $status = 200,
        private array $headers = []
    ) {
        http_response_code($this->status);
    }

    public function send(): void
    {
        echo $this->content;
    }

    public function setContent(?string $content): Response
    {
        $this->content = $content;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getHeader(string $header): mixed
    {
        return $this->headers[$header];
    }
}