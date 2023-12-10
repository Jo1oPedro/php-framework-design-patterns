<?php

namespace Cascata\Framework\Session;

interface SessionInterface
{
    public function set(string $key, mixed $value): void;
    public function get(string $key, mixed $default = null): mixed;
    public function has(string $key): bool;
    public function remove(string $key): void;
    public function getFlash(string $type): array;
    public function setFlash(string $type, string $message): void;
    public function hasFlash(string $type): bool;
    public function clearFlash(): void;
}