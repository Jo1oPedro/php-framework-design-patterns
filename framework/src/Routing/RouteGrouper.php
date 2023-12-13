<?php

namespace Cascata\Framework\Routing;

use Cascata\Framework\GlobalContainer\Container;
use Cascata\Framework\Http\Response;

class RouteGrouper
{
    public function __construct(string $faviconPath = "/public/images/favicon.ico")
    {
        $this->addRoute('GET', $faviconPath, function() {

            $basePath = Container::getInstance()->get('BASE_PATH');
            $imagePath = $basePath . "/public/images/favicon.ico";
            $contentType = mime_content_type($imagePath);

            $response = new Response(
                file_get_contents($basePath . "/public/images/favicon.ico"),
                headers: [
                    'Content-Type' => $contentType,
                    'Content-Disposition: inline; filename="' . basename($imagePath) . '"'
                ]
            );
            return $response;
        });
    }

    private array $routes = [];

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function addRoute(string $httpMethod, string $path, array|callable $handler): void
    {
        $this->routes[$httpMethod . "/" . $path] = [$httpMethod, $path, $handler];
    }
}