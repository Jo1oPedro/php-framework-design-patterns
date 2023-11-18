<?php

namespace Cascata\Framework\Http;

class Request
{
    public function __construct(
        public readonly array $getParams,
        public readonly array $postParams,
        public readonly array $cookies,
        public readonly array $files,
        public readonly array $server
    ) {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getPathInfo(): string
    {
        $requestUri = str_replace("/frameworkPatterns/", "", $this->server['REQUEST_URI']);
        return strtok($requestUri, '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}