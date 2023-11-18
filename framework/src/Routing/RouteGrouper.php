<?php

namespace Cascata\Framework\Routing;

class RouteGrouper
{
    private array $routes = [];

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function addRoute(string $httpMethod, string $path, array|callable $handler): void
    {
        $this->routes[$path] = [$httpMethod, $path, $handler];
    }
}