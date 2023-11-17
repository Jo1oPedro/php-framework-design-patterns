<?php

namespace Cascata\Framework\Routing;

use Cascata\Framework\Http\Exceptions\HttpException;
use Cascata\Framework\Http\Exceptions\HttpRequestMethodException;
use Cascata\Framework\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Cascata\Framework\Routing\RouteGrouper;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private Dispatcher $dispatcher;

    public function __construct()
    {
        // Creates a dispatcher
        $this->dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            include BASE_PATH . "/routes/web.php";

            /** @var RouteGrouper $routeGrouper */
            foreach($routeGrouper->getRoutes() as $route) {
                $routeCollector->addRoute(...$route);
            }
        });
    }

    /**
     * @throws HttpException
     * @throws HttpRequestMethodException
     */
    public function dispatch(Request $request): array
    {
        $routeInfo = $this->extractRouteInfo($request);

        [$handler, $vars] = $routeInfo;

        [$controller, $method] = $handler;

        return [[new $controller, $method], $vars];
    }

    /**
     * @throws HttpRequestMethodException
     * @throws HttpException
     */
    private function extractRouteInfo(Request $request)
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]]; // routeHandler, vars
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                throw new HttpRequestMethodException("The allowed methods are $allowedMethods");
            default:
                throw new HttpException("Not found");
        }
    }
}