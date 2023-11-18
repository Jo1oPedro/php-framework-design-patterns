<?php

namespace Cascata\Framework\Routing;

use Cascata\Framework\Http\Exceptions\HttpException;
use Cascata\Framework\Http\Exceptions\HttpRequestMethodException;
use Cascata\Framework\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
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

        if(is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
    }

    /**
     * @throws HttpRequestMethodException
     * @throws HttpException
     */
    private function extractRouteInfo(Request $request): array
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
                $excepetion = new HttpRequestMethodException("The allowed methods are $allowedMethods");
                $excepetion->setStatusCode(405);
                throw $excepetion;
            default:
                $excepetion = new HttpException("Not found");
                $excepetion->setStatusCode(404);
                throw $excepetion;
        }
    }
}